<?php
namespace App\Jobs;

use App\Models\Event;
use App\Models\Setting;
use App\Services\N8nService;
use App\Services\ThreadsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TriggerN8nWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public array $payload) {}

    public function handle(N8nService $n8n, ThreadsService $threads): void
    {
        $eventType = $this->payload['event_type'] ?? '';

        try {
            if ($eventType === 'event_created') {
                $event = Event::with(['salePhases', 'ticketCategories'])
                    ->find($this->payload['event_id']);

                if (!$event) return;

                $fullPayload = $n8n->buildEventCreatedPayload($event);
                $n8n->trigger('event_created', $fullPayload);

                // Post ke Threads juga
                $threads->postEventAnnouncement($event, $fullPayload['threads_caption']);

            } elseif ($eventType === 'event_finished') {
            $event = Event::with([
                'salePhases',
                'ticketCategories',
            ])->find($this->payload['event_id']);

            if (!$event) return;

            $fullPayload     = $n8n->buildEventFinishedPayload($event);
            $telegramService = app(\App\Services\TelegramService::class);
            $watermarkService= app(\App\Services\ImageWatermarkService::class);
            $groupChatId     = \App\Models\Setting::get('telegram_group_chat_id', '');

            // Generate foto rekap dengan watermark
            $rekapImagePath = $watermarkService->generateRekapImage($event);

            if ($groupChatId) {
                if ($rekapImagePath && file_exists($rekapImagePath)) {
                    // Kirim foto + caption ke Telegram group
                    $telegramService->sendEventRekapWithPhoto(
                        $groupChatId,
                        $rekapImagePath,
                        $fullPayload['telegram_message']
                    );
                } else {
                    // Fallback teks saja
                    $telegramService->sendMessage(
                        $groupChatId,
                        $fullPayload['telegram_message']
                    );
                }
            }

            // Kirim ke Threads (teks + banner event)
            if (Setting::get('threads_auto_post', '0') === '1') {
                $threads->postEventAnnouncement(
                    $event,
                    $fullPayload['threads_caption']
                );
            }

            // Trigger n8n
            $n8n->trigger('event_finished', $fullPayload);

            // Cleanup temp file
            if ($rekapImagePath) {
                $watermarkService->cleanup($rekapImagePath);
            }
        }

        } catch (\Exception $e) {
            Log::error("TriggerN8nWebhook [{$eventType}] failed: " . $e->getMessage());
            throw $e;
        }
    }
}