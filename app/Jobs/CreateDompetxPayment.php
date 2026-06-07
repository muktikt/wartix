<?php
namespace App\Jobs;

use App\Models\Order;
use App\Services\DompetxService;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateDompetxPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $orderId) {}

    public function handle(DompetxService $dompetx, TelegramService $telegram): void
    {
        $order = Order::with(['event', 'salePhase', 'ticketCategory'])->find($this->orderId);

        if (!$order) {
            Log::warning("CreateDompetxPayment: order {$this->orderId} not found");
            return;
        }

        $paymentData = $dompetx->createPayment($order);

        if (!$paymentData) {
            Log::error("CreateDompetxPayment: failed for order {$order->order_code}");
            return;
        }

        // Kirim QRIS ke Telegram
        $chatId = $order->telegram_chat_id
            ?? $order->telegramConnection?->telegram_chat_id;

        if ($chatId) {
            // Kirim info payment dulu
            dispatch(new SendTelegramNotification([
                'type'     => 'payment_info',
                'order_id' => $order->id,
                'chat_id'  => $chatId,
            ]));

            // Kirim foto QRIS jika ada URL
            if (!empty($paymentData['qris_url'])) {
                $telegram->sendPhoto($chatId, $paymentData['qris_url'],
                    "QRIS untuk order {$order->order_code}\nBerlaku " .
                    \App\Models\Setting::get('payment_expired_minutes', 10) . " menit"
                );
            }
        }
    }
}