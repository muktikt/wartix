<?php
namespace App\Services;

use App\Models\Setting;
use App\Models\Event;
use App\Models\SuccessLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nService
{
    private string $webhookUrl;
    private string $secret;

    public function __construct()
    {
        $this->webhookUrl = Setting::get('n8n_webhook_url', '');
        $this->secret     = Setting::get('n8n_webhook_secret', '');
    }

    public function trigger(string $eventType, array $payload): bool
    {
        if (!$this->webhookUrl) {
            Log::warning('n8n webhook URL not configured');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'X-Wartix-Secret' => $this->secret,
                'Content-Type'    => 'application/json',
            ])->post($this->webhookUrl, array_merge(
                ['event_type' => $eventType],
                $payload
            ));

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("n8n trigger [{$eventType}] failed: " . $e->getMessage());
            return false;
        }
    }

    public function buildEventCreatedPayload(Event $event): array
    {
        $phases     = $event->salePhases->pluck('name')->join(' | ');
        $categories = $event->ticketCategories->map(fn($c) =>
            "• {$c->name} — Rp " . number_format($c->fee_per_ticket) . "/tiket"
        )->join("\n");

        return [
            'event_id'    => $event->id,
            'event_title' => $event->title,
            'artist'      => $event->artist_name,
            'venue'       => $event->venue,
            'city'        => $event->city,
            'event_date'  => $event->event_date->format('d M Y'),
            'phases'      => $phases,
            'categories'  => $categories,
            'order_url'   => url("/events/{$event->slug}"),
            'banner_url'  => $event->banner_image ? asset("storage/{$event->banner_image}") : null,
            'seatplan_url'=> $event->seatplan_image ? asset("storage/{$event->seatplan_image}") : null,

            // Format Telegram
            'telegram_message' => $this->buildAnnouncementMessage($event, $phases, $categories),

            // Format Threads caption
            'threads_caption'  => $this->buildThreadsCaption($event, $phases, $categories),
        ];
    }

    public function buildEventFinishedPayload(Event $event): array
    {
        $successLogs  = SuccessLog::where('event_id', $event->id)
            ->where('status', 'success')
            ->with(['salePhase', 'ticketCategory'])
            ->get();

        $totalSuccess = $successLogs->count();

        $byPhase = $successLogs->groupBy('sale_phase_id')->map(function ($logs) {
            $phase = $logs->first()->salePhase->name ?? 'Unknown';
            $cats  = $logs->groupBy('ticket_category_id')->map(function ($catLogs) {
                $cat   = $catLogs->first()->ticketCategory->name ?? 'Unknown';
                $count = $catLogs->count();
                return "• {$cat} — {$count} Success";
            })->join("\n");
            return "🎟 {$phase}\n{$cats}";
        })->join("\n\n");

        $message  = "✅ EVENT FINISHED — Wartix\n\n";
        $message .= "{$event->title}\n\n";
        $message .= $byPhase . "\n\n";
        $message .= "📊 TOTAL SUCCESS: {$totalSuccess} Orders\n\n";
        $message .= "Thank you for trusting Wartix.";

        // Generate rekap image dengan watermark
        $rekapImagePath = null;
        try {
            $watermark      = app(\App\Services\ImageWatermarkService::class);
            $rekapImagePath = $this->generateRekapImageWithText($event, $successLogs, $totalSuccess, $byPhase, $watermark);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Generate rekap image failed: ' . $e->getMessage());
        }

        return [
            'event_id'          => $event->id,
            'event_title'       => $event->title,
            'total_success'     => $totalSuccess,
            'telegram_message'  => $message,
            'rekap_image_path'  => $rekapImagePath,
        ];
    }

    private function generateRekapImageWithText(
        Event $event,
        $successLogs,
        int $totalSuccess,
        string $byPhase,
        \App\Services\ImageWatermarkService $watermark
    ): ?string {
        // Buat HTML rekap → convert ke gambar
        // Untuk sekarang pakai GD langsung

        $width   = 1080;
        $height  = 1350;
        $manager = new \Intervention\Image\ImageManager(
            new \Intervention\Image\Drivers\Gd\Driver()
        );

        // Background gelap premium
        $image = $manager->create($width, $height)->fill('#0F172A');

        // Simpan sementara untuk ditambah watermark
        $tempPath = storage_path('app/temp/rekap_base_' . time() . '.jpg');
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }
        $image->save($tempPath);

        // Tambah watermark
        return $watermark->addWatermark($tempPath);
    }

    private function buildAnnouncementMessage(Event $event, string $phases, string $categories): string
    {
        return "🎫 NEW EVENT AVAILABLE — Wartix\n\n"
            . "{$event->title}\n\n"
            . "🎟 {$phases}\n\n"
            . "{$categories}\n\n"
            . "📍 Venue:\n{$event->venue}, {$event->city}\n\n"
            . "📅 {$event->event_date->format('d M Y')}\n\n"
            . "🔗 Order sekarang:\n" . url("/events/{$event->slug}");
    }

    private function buildThreadsCaption(Event $event, string $phases, string $categories): string
    {
        $hashtags = '#Wartix #WarTiket #' . str_replace(' ', '', $event->city);

        return "🎫 {$event->title}\n\n"
            . "📍 {$event->venue}, {$event->city}\n"
            . "📅 {$event->event_date->format('d M Y')}\n\n"
            . "🎟 {$phases}\n\n"
            . "{$categories}\n\n"
            . "🔗 Order sekarang:\n" . url("/events/{$event->slug}") . "\n\n"
            . $hashtags;
    }
}