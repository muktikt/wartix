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
            $totalAccounts = Order::distinct('email')->count('email');
            $successAccounts = Order::where('order_status', 'success')
                ->distinct('email')
                ->count('email');

            return [
                'success_rate'   => $this->getSuccessRate(),
                'total_accounts' => $totalAccounts,
                'success_accounts' => $successAccounts,
                'total_events'   => Event::count(),
                'active_events'  => Event::whereIn('status', ['upcoming', 'ongoing'])->count(),
            ];
        });

        $activeEvents = Cache::remember('active_events', 15, function () {
            return Event::whereIn('status', ['upcoming', 'ongoing'])
                ->with(['salePhases', 'ticketCategories'])
                ->latest()
                ->limit(6)
                ->get();
        });

        $activeEvents = $activeEvents->map(fn (Event $event) => $this->attachAccountStats($event));

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
        $totalAccounts = Order::distinct('email')->count('email');
        $successAccounts = Order::where('order_status', 'success')
            ->distinct('email')
            ->count('email');

        return $totalAccounts > 0
            ? round(($successAccounts / $totalAccounts) * 100, 1)
            : 0.0;
    }

    private function attachAccountStats(Event $event): Event
    {
        $totalAccounts = Order::where('event_id', $event->id)
            ->distinct('email')
            ->count('email');

        $successAccounts = Order::where('event_id', $event->id)
            ->where('order_status', 'success')
            ->distinct('email')
            ->count('email');

        $event->setAttribute('total_accounts', $totalAccounts);
        $event->setAttribute('success_accounts', $successAccounts);
        $event->setAttribute('success_rate', $totalAccounts > 0
            ? round(($successAccounts / $totalAccounts) * 100, 1)
            : 0.0);
        $event->setAttribute('total_slots', $event->resolved_total_slots);
        $event->setAttribute('available_slots', $event->resolved_available_slots);

        return $event;
    }
}
