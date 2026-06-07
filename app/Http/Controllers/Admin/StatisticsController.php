<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use App\Models\SuccessLog;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $ordersByDay = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN order_status = "success" THEN 1 ELSE 0 END) as success')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $successByEvent = Event::withCount([
                'orders as success_count' => fn($q) => $q->where('order_status', 'success')
            ])
            ->having('success_count', '>', 0)
            ->orderByDesc('success_count')
            ->limit(10)
            ->get();

        $revenueByMonth = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(grand_total) as revenue')
            )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        $paymentStatus = Order::select('payment_status', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_status')
            ->get();

        return view('admin.statistics.index', compact(
            'ordersByDay', 'successByEvent', 'revenueByMonth', 'paymentStatus'
        ));
    }
}