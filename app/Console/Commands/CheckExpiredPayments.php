<?php
namespace App\Console\Commands;

use App\Models\Order;
use App\Models\PaymentLog;
use App\Jobs\SendTelegramNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredPayments extends Command
{
    protected $signature   = 'wartix:check-expired-payments';
    protected $description = 'Check and update expired payments';

    public function handle(): void
    {
        $expiredLogs = PaymentLog::where('status', 'pending')
            ->where('expired_at', '<', now())
            ->with('order')
            ->get();

        foreach ($expiredLogs as $log) {
            $log->update(['status' => 'expired']);

            $order = $log->order;
            if (!$order) continue;

            $order->update(['payment_status' => 'expired']);

            $chatId = $order->telegram_chat_id
                ?? $order->telegramConnection?->telegram_chat_id;

            if ($chatId) {
                dispatch(new SendTelegramNotification([
                    'type'       => 'payment_expired',
                    'order_id'   => $order->id,
                    'order_code' => $order->order_code,
                    'chat_id'    => $chatId,
                ]));
            }

            Log::info("Payment expired: {$log->payment_reference}");
        }

        $this->info("Checked {$expiredLogs->count()} expired payments.");
    }
}