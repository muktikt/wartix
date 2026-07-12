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

        // Skema HARUS sama persis dengan HomeController::index(),
        // karena keduanya menulis ke cache key yang sama.
        Cache::remember('home_stats', 300, function () {
            $totalAccounts   = Order::distinct('email')->count('email');
            $successAccounts = Order::where('order_status', 'success')
                ->distinct('email')
                ->count('email');

            return [
                'success_rate'     => $totalAccounts > 0
                    ? round(($successAccounts / $totalAccounts) * 100, 1)
                    : 0.0,
                'total_accounts'   => $totalAccounts,
                'success_accounts' => $successAccounts,
                'total_events'     => Event::count(),
                'active_events'    => Event::whereIn('status', ['upcoming', 'ongoing'])->count(),
            ];
        });

        Cache::remember('active_events', 60, function () {
            return Event::whereIn('status', ['upcoming', 'ongoing', 'finished'])
                ->with(['salePhases', 'ticketCategories'])
                ->latest()
                ->limit(6)
                ->get();
        });

        $this->info('Cache warmed successfully!');
    }
}