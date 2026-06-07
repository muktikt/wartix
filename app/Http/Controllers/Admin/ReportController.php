<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use App\Models\SuccessLog;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['event', 'salePhase', 'ticketCategory']);

        if ($eventId = $request->get('event_id')) {
            $query->where('event_id', $eventId);
        }

        if ($status = $request->get('order_status')) {
            $query->where('order_status', $status);
        }

        if ($payment = $request->get('payment_status')) {
            $query->where('payment_status', $payment);
        }

        if ($from = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total_orders'   => $query->toBase()->getCountForPagination(),
            'success_orders' => (clone $query)->where('order_status', 'success')->count(),
            'pending_orders' => (clone $query)->where('order_status', 'waiting')->count(),
            'failed_orders'  => (clone $query)->whereIn('order_status', ['failed','cancelled'])->count(),
            'total_revenue'  => (clone $query)->where('payment_status', 'paid')->sum('grand_total'),
            'total_fee'      => (clone $query)->where('payment_status', 'paid')->sum('service_fee_total'),
        ];

        $events = Event::orderBy('title')->get();

        return view('admin.reports.index', compact('orders', 'stats', 'events'));
    }
}