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

                $fullPayload     = $n8n->buildEventCreatedPayload($event);
                $telegramService = app(\App\Services\TelegramService::class);
                $groupChatId     = Setting::get('telegram_group_chat_id', '');

                // Kirim langsung foto banner + caption pengumuman ke Telegram Group
                if ($groupChatId) {
                    $bannerPath = null;
                    if ($event->banner_image) {
                        $diskPath = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->path($event->banner_image);
                        if (file_exists($diskPath)) {
                            $bannerPath = $diskPath;
                        }
                    }

                    if ($bannerPath) {
                        $telegramService->sendEventRekapWithPhoto(
                            $groupChatId,
                            $bannerPath,
                            $fullPayload['telegram_message']
                        );
                    } else {
                        $telegramService->sendMessage(
                            $groupChatId,
                            $fullPayload['telegram_message']
                        );
                    }
                }

                // Post ke Threads jika aktif
                if (Setting::get('threads_auto_post', '0') === '1') {
                    $threads->postEventAnnouncement($event, $fullPayload['threads_caption']);
                }

                // Trigger n8n
                $n8n->trigger('event_created', $fullPayload);

            } elseif ($eventType === 'order_created') {
                $order = \App\Models\Order::withoutGlobalScope(\App\Models\Scopes\HideUnlinkedOrdersScope::class)
                    ->with('event')
                    ->find($this->payload['order_id']);

                if (!$order) return;

                $n8n->triggerAdmin('order_created', $n8n->buildOrderCreatedPayload($order));

            } elseif ($eventType === 'payment_paid') {
                $order = \App\Models\Order::withoutGlobalScope(\App\Models\Scopes\HideUnlinkedOrdersScope::class)
                    ->with('event')
                    ->find($this->payload['order_id']);

                if (!$order) return;

                $n8n->triggerAdmin('payment_paid', $n8n->buildPaymentPaidPayload($order));

            } elseif ($eventType === 'event_finished') {
                $event = Event::with([
                    'salePhases',
                    'ticketCategories',
                ])->find($this->payload['event_id']);

                if (!$event) return;

                $fullPayload     = $n8n->buildEventFinishedPayload($event);
                $telegramService = app(\App\Services\TelegramService::class);
                $watermarkService= app(\App\Services\ImageWatermarkService::class);
                $groupChatId     = Setting::get('telegram_group_chat_id', '');

                // Generate foto rekap dengan watermark & kirim ke Telegram group
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

                // Trigger n8n jika diperlukan integrasi eksternal
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