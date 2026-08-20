<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use App\Models\Scopes\HideUnlinkedOrdersScope;
use App\Models\SuccessLog;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        Order::cancelExpiredUnlinkedOrders();

        $stats = [
            'total_orders'    => Order::withoutGlobalScope(HideUnlinkedOrdersScope::class)->count(),
            'success_orders'  => Order::withoutGlobalScope(HideUnlinkedOrdersScope::class)->where('order_status', 'success')->count(),
            'pending_orders'  => Order::withoutGlobalScope(HideUnlinkedOrdersScope::class)->where('order_status', 'waiting')->count(),
            'failed_orders'   => Order::withoutGlobalScope(HideUnlinkedOrdersScope::class)->whereIn('order_status', ['failed', 'cancelled'])->count(),
            'active_events'   => Event::whereIn('status', ['upcoming', 'ongoing'])->count(),
            'total_revenue'   => Order::withoutGlobalScope(HideUnlinkedOrdersScope::class)->where('payment_status', 'paid')->sum('grand_total'),
            'pending_link_count' => Order::withoutGlobalScope(HideUnlinkedOrdersScope::class)
                ->where('order_status', 'pending_link')->count(),
            'success_rate'    => 0,
        ];

        $total = $stats['total_orders'];
        $stats['success_rate'] = $total > 0
            ? round(($stats['success_orders'] / $total) * 100, 1)
            : 0;

        $recentOrders  = Order::withoutGlobalScope(HideUnlinkedOrdersScope::class)
            ->with(['event', 'salePhase', 'ticketCategory'])
            ->latest()
            ->limit(10)
            ->get();

        $activeEvents  = Event::whereIn('status', ['upcoming', 'ongoing'])
            ->withCount('orders')
            ->latest()
            ->limit(5)
            ->get();

        $recentSuccess = SuccessLog::with(['event', 'salePhase', 'ticketCategory'])
            ->latest()
            ->limit(10)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats'         => $stats,
            'recentOrders'  => $recentOrders,
            'activeEvents'  => $activeEvents,
            'recentSuccess' => $recentSuccess,
        ]);
    }
}