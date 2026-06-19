<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['event', 'salePhase', 'ticketCategory'])
            ->latest();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('telegram_username', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('order_status')) {
            $query->where('order_status', $status);
        }

        if ($payment = $request->get('payment_status')) {
            $query->where('payment_status', $payment);
        }

        if ($eventId = $request->get('event_id')) {
            $query->where('event_id', $eventId);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'event',
            'salePhase',
            'ticketCategory',
            'guests',
            'customFieldAnswers.customField',
            'successLog',
            'paymentLog',
            'telegramConnection',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:waiting,processing,success,failed,cancelled',
        ]);

        $order->update(['order_status' => $request->order_status]);

        // Invalidate cache agar slot update real-time
        Cache::forget('active_events');
        Cache::forget('home_stats');

        return back()->with('success', 'Status order berhasil diupdate.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        Cache::forget('active_events');
        Cache::forget('home_stats');

        return redirect()->route('admin.orders.index')->with('success', 'Order berhasil dihapus.');
    }
}