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
    public int $backoff = 30;

    public function __construct(public int $orderId) {}

    public function handle(DompetxService $dompetx, TelegramService $telegram): void
    {
        $order = Order::with(['event', 'salePhase', 'ticketCategory'])
            ->find($this->orderId);

        if (!$order) {
            Log::warning("CreateDompetxPayment: order {$this->orderId} not found");
            return;
        }

        // Jangan buat payment kalau sudah paid
        if ($order->payment_status === 'paid') {
            Log::info("CreateDompetxPayment: order {$order->order_code} already paid");
            return;
        }

        $paymentData = $dompetx->createPayment($order);

        $chatId = $order->telegram_chat_id
            ?? $order->telegramConnection?->telegram_chat_id;

        if (!$chatId) {
            Log::warning("CreateDompetxPayment: no chat_id for order {$order->order_code}");
            return;
        }

        if (!$paymentData) {
            Log::error("CreateDompetxPayment: failed for order {$order->order_code}");

            // Tetap kirim info manual ke user kalau payment gagal dibuat
            $telegram->sendMessage($chatId,
                "⚠️ <b>Info Pembayaran — Wartix</b>\n\n" .
                "Tiket untuk order <code>{$order->order_code}</code> berhasil!\n\n" .
                "Silakan hubungi admin untuk informasi pembayaran."
            );
            return;
        }

        // Kirim payment info ke Telegram
        dispatch(new SendTelegramNotification([
            'type'     => 'payment_info',
            'order_id' => $order->id,
            'chat_id'  => $chatId,
        ]));

        // Kirim QRIS image kalau ada URL nya
        $qrisUrl = $paymentData['qris_url']
            ?? $paymentData['payment_url']
            ?? $paymentData['data']['qris_url']
            ?? null;

        if ($qrisUrl) {
            // Delay 2 detik biar pesan info payment terkirim dulu
            sleep(2);
            $telegram->sendPhoto(
                $chatId,
                $qrisUrl,
                "Scan QRIS untuk membayar fee jasa Wartix\n" .
                "Order: {$order->order_code}\n" .
                "Total: Rp " . number_format($order->grand_total)
            );
        }

        Log::info("CreateDompetxPayment: success for order {$order->order_code}");
    }
}