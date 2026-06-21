<?php
namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Scopes\HideUnlinkedOrdersScope;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CancelUnlinkedOrders extends Command
{
    protected $signature   = 'wartix:cancel-unlinked-orders';
    protected $description = 'Cancel orders yang belum klik Start Telegram setelah 10 menit';

    public function handle(): void
    {
        $expired = Order::withoutGlobalScope(HideUnlinkedOrdersScope::class)
            ->where('order_status', 'pending_link')
            ->where('created_at', '<', now()->subMinutes(10))
            ->get();

        foreach ($expired as $order) {
            $order->update(['order_status' => 'cancelled']);
            Log::info("Order auto-cancelled (unlinked Telegram timeout): {$order->order_code}");
        }

        if ($expired->isNotEmpty()) {
            Cache::forget('active_events');
            Cache::forget('home_stats');
        }

        $this->info("Cancelled {$expired->count()} unlinked orders.");
    }
}