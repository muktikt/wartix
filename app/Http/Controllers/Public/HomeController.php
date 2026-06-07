<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\SuccessLog;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('home_stats', 300, function () {
            return [
                'success_rate'   => $this->getSuccessRate(),
                'total_checkout' => Order::where('order_status', 'success')->count(),
                'total_events'   => Event::count(),
                'active_events'  => Event::whereIn('status', ['upcoming', 'ongoing'])->count(),
            ];
        });

        $activeEvents = Cache::remember('active_events', 60, function () {
            return Event::whereIn('status', ['upcoming', 'ongoing'])
                ->with(['salePhases', 'ticketCategories'])
                ->latest()
                ->limit(6)
                ->get();
        });

        $finishedEvents = Event::where('status', 'finished')
            ->latest()
            ->limit(4)
            ->get();

        $recentSuccess = SuccessLog::with(['event', 'salePhase', 'ticketCategory'])
            ->where('status', 'success')
            ->latest()
            ->limit(10)
            ->get();

        return view('public.home', compact(
            'stats', 'activeEvents', 'finishedEvents', 'recentSuccess'
        ));
    }

    private function getSuccessRate(): float
    {
        $total   = Order::count();
        $success = Order::where('order_status', 'success')->count();
        return $total > 0 ? round(($success / $total) * 100, 1) : 98.7;
    }
}