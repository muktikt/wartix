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
        $count = Order::cancelExpiredUnlinkedOrders();
        $this->info("Cancelled {$count} unlinked orders.");
    }
}