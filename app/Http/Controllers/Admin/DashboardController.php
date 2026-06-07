<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use App\Models\SuccessLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'   => Order::count(),
            'success_orders' => Order::where('order_status', 'success')->count(),
            'pending_orders' => Order::where('order_status', 'waiting')->count(),
            'failed_orders'  => Order::whereIn('order_status', ['failed', 'cancelled'])->count(),
            'active_events'  => Event::whereIn('status', ['upcoming', 'ongoing'])->count(),
            'total_revenue'  => Order::where('payment_status', 'paid')->sum('grand_total'),
            'success_rate'   => 0,
        ];

        $total = $stats['total_orders'];
        $stats['success_rate'] = $total > 0
            ? round(($stats['success_orders'] / $total) * 100, 1)
            : 0;

        $recentOrders  = Order::with(['event', 'salePhase', 'ticketCategory'])
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

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'activeEvents', 'recentSuccess'
        ));
    }
}