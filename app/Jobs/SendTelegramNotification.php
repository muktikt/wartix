<?php
namespace App\Jobs;

use App\Models\Order;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public array $data) {}

    public function handle(TelegramService $telegram): void
    {
        $type   = $this->data['type'] ?? '';
        $chatId = $this->data['chat_id'] ?? null;

        if (!$chatId) {
            Log::warning('Telegram notification: no chat_id');
            return;
        }

        match($type) {
            'payment_info'    => $this->sendPaymentInfo($telegram, $chatId),
            'payment_paid'    => $telegram->sendPaymentPaidNotif($chatId, $this->data['order_code']),
            'payment_expired' => $telegram->sendPaymentExpiredNotif($chatId, $this->data['order_code']),
            'success_notif'   => $this->sendSuccessNotif($telegram, $chatId),
            default           => Log::warning("Unknown Telegram notification type: {$type}"),
        };
    }

    private function sendPaymentInfo(TelegramService $telegram, string $chatId): void
    {
        $order = Order::with(['event', 'salePhase', 'ticketCategory'])->find($this->data['order_id']);
        if (!$order) return;

        $telegram->sendPaymentInfo($chatId, [
            'event'        => $order->event->title,
            'phase'        => $order->salePhase->name,
            'category'     => $order->ticketCategory->name,
            'qty'          => $order->qty,
            'ticket_price' => $order->ticket_price_total,
            'service_fee'  => $order->service_fee_total,
            'admin_fee'    => $order->admin_fee,
            'grand_total'  => $order->grand_total,
        ]);
    }

    private function sendSuccessNotif(TelegramService $telegram, string $chatId): void
    {
        $order = Order::with(['event', 'salePhase', 'ticketCategory'])->find($this->data['order_id']);
        if (!$order) return;

        $telegram->sendSuccessNotif($chatId, [
            'event'    => $order->event->title,
            'phase'    => $order->salePhase->name,
            'category' => $order->ticketCategory->name,
            'qty'      => $order->qty,
        ]);
    }
}