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
                $event = Event::find($this->payload['event_id']);
                if (!$event) return;

                $fullPayload = $n8n->buildEventFinishedPayload($event);
                $n8n->trigger('event_finished', $fullPayload);

            } else {
                $n8n->trigger($eventType, $this->payload);
            }

        } catch (\Exception $e) {
            Log::error("TriggerN8nWebhook [{$eventType}] failed: " . $e->getMessage());
            throw $e;
        }
    }
}