<?php
namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmCache extends Command
{
    protected $signature   = 'wartix:warm-cache';
    protected $description = 'Warm up application cache';

    public function handle(): void
    {
        $this->info('Warming cache...');

        Cache::forget('home_stats');
        Cache::forget('active_events');

        Cache::remember('home_stats', 300, function () {
            $total   = Order::count();
            $success = Order::where('order_status', 'success')->count();
            return [
                'success_rate'   => $total > 0 ? round(($success / $total) * 100, 1) : 98.7,
                'total_checkout' => $success,
                'total_events'   => Event::count(),
                'active_events'  => Event::whereIn('status', ['upcoming','ongoing'])->count(),
            ];
        });

        Cache::remember('active_events', 60, function () {
            return Event::whereIn('status', ['upcoming','ongoing'])
                ->with(['salePhases','ticketCategories'])
                ->latest()
                ->limit(6)
                ->get();
        });

        $this->info('Cache warmed successfully!');
    }
}