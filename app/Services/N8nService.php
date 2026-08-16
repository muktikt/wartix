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
    private string $adminWebhookUrl;
    private string $adminSecret;

    public function __construct()
    {
        $this->webhookUrl      = Setting::get('n8n_webhook_url', '');
        $this->secret          = Setting::get('n8n_webhook_secret', '');
        $this->adminWebhookUrl = Setting::get('n8n_admin_webhook_url', '');
        $this->adminSecret     = Setting::get('n8n_admin_webhook_secret', '');
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

    /**
     * Kirim ke webhook n8n khusus notifikasi admin (order baru & payment fee),
     * yang terpisah dari webhook n8n utama dan bisa pakai bot Telegram berbeda.
     */
    public function triggerAdmin(string $eventType, array $payload): bool
    {
        if (!$this->adminWebhookUrl) {
            Log::warning('n8n admin notification webhook URL not configured');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'X-Wartix-Secret' => $this->adminSecret,
                'Content-Type'    => 'application/json',
            ])->post($this->adminWebhookUrl, array_merge(
                ['event_type' => $eventType],
                $payload
            ));

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("n8n admin trigger [{$eventType}] failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Payload notifikasi order baru masuk, untuk admin (bukan customer).
     */
    public function buildOrderCreatedPayload(\App\Models\Order $order): array
    {
        $event = $order->event;

        $lines   = [];
        $lines[] = '🆕 <b>Order Baru Masuk</b>';
        $lines[] = '';
        $lines[] = "🎫 Event: {$event->title}";
        $lines[] = "🌐 Platform: " . strtoupper($event->platform_type ?? '-');
        $lines[] = "👤 Nama: {$order->full_name}";
        $lines[] = "📱 HP: {$order->phone_number}";
        $lines[] = "📧 Email: {$order->email}";
        $lines[] = "📸 Instagram: @{$order->telegram_username}";
        $lines[] = "🎟 Qty: {$order->qty}";
        $lines[] = '💰 Total: Rp ' . number_format($order->grand_total, 0, ',', '.');
        $lines[] = "🔖 Order Code: <code>{$order->order_code}</code>";
        $lines[] = '';
        $lines[] = 'Lihat detail: ' . route('admin.orders.show', $order->id);

        return [
            'order_id'         => $order->id,
            'order_code'       => $order->order_code,
            'event_title'      => $event->title ?? '-',
            'full_name'        => $order->full_name,
            'grand_total'      => $order->grand_total,
            'telegram_message' => implode("\n", $lines),
        ];
    }

    /**
     * Payload notifikasi payment fee sudah diterima, untuk admin (bukan customer).
     */
    public function buildPaymentPaidPayload(\App\Models\Order $order): array
    {
        $event = $order->event;

        $lines   = [];
        $lines[] = '✅ <b>Payment Fee Diterima</b>';
        $lines[] = '';
        $lines[] = "🎫 Event: {$event->title}";
        $lines[] = "👤 Nama: {$order->full_name}";
        $lines[] = '💰 Total: Rp ' . number_format($order->grand_total, 0, ',', '.');
        $lines[] = "🔖 Order Code: <code>{$order->order_code}</code>";
        $lines[] = '';
        $lines[] = 'Lihat detail: ' . route('admin.orders.show', $order->id);

        return [
            'order_id'         => $order->id,
            'order_code'       => $order->order_code,
            'event_title'      => $event->title ?? '-',
            'full_name'        => $order->full_name,
            'grand_total'      => $order->grand_total,
            'telegram_message' => implode("\n", $lines),
        ];
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
            'banner_url'  => $event->banner_url,
            'seatplan_url'=> $event->seatplan_url,

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

        $message  = "✅ EVENT FINISHED — Warindong\n\n";
        $message .= "{$event->title}\n\n";
        $message .= $byPhase . "\n\n";
        $message .= "📊 TOTAL SUCCESS: {$totalSuccess} Orders\n\n";
        $message .= "Thank you for trusting Warindong.";

        return [
            'event_id'         => $event->id,
            'event_title'      => $event->title,
            'total_success'    => $totalSuccess,
            'telegram_message' => $message,
            'threads_caption'  => $this->buildFinishedThreadsCaption($event, $totalSuccess),
        ];
    }

    private function buildFinishedThreadsCaption(Event $event, int $totalSuccess): string
    {
        $hashtags = '#Warindong #WarTiket #' . str_replace(' ', '', $event->city);

        return "✅ {$event->title} — SELESAI\n\n"
            . "📊 Total Success: {$totalSuccess} orders\n\n"
            . "Terima kasih sudah mempercayai Warindong.\n\n"
            . $hashtags;
    }

    private function buildAnnouncementMessage(Event $event, string $phases, string $categories): string
    {
        return "🎫 NEW EVENT AVAILABLE — Warindong\n\n"
            . "{$event->title}\n\n"
            . "🎟 {$phases}\n\n"
            . "{$categories}\n\n"
            . "📍 Venue:\n{$event->venue}, {$event->city}\n\n"
            . "📅 {$event->event_date->format('d M Y')}\n\n"
            . "🔗 Order sekarang:\n" . url("/events/{$event->slug}");
    }
}