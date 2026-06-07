<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderGuest;
use App\Models\SuccessLog;
use App\Services\MaskService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function orders(Request $request)
    {
        $orders = Order::with(['event', 'salePhase', 'ticketCategory'])
            ->when($request->event_id, fn($q) => $q->where('event_id', $request->event_id))
            ->when($request->order_status, fn($q) => $q->where('order_status', $request->order_status))
            ->latest()
            ->get();

        $filename = 'wartix-orders-' . now()->format('Ymd-His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Order Code', 'Event', 'Sale Phase', 'Category',
                'Qty', 'Name', 'Email', 'Phone',
                'Order Status', 'Payment Status', 'Grand Total', 'Created At'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_code,
                    $order->event->title ?? '-',
                    $order->salePhase->name ?? '-',
                    $order->ticketCategory->name ?? '-',
                    $order->qty,
                    $order->full_name,
                    $order->email,
                    $order->phone_number,
                    $order->order_status,
                    $order->payment_status,
                    $order->grand_total,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function guests(Request $request)
    {
        $guests = OrderGuest::with(['order.event', 'order.salePhase', 'order.ticketCategory'])
            ->when($request->event_id, fn($q) => $q->whereHas('order', fn($o) => $o->where('event_id', $request->event_id)))
            ->get();

        $filename = 'wartix-guests-' . now()->format('Ymd-His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($guests) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Order Code', 'Event', 'Ticket Position',
                'Guest Type', 'Name', 'Identity Number'
            ]);

            foreach ($guests as $guest) {
                fputcsv($file, [
                    $guest->order->order_code ?? '-',
                    $guest->order->event->title ?? '-',
                    $guest->ticket_position,
                    $guest->guest_type,
                    $guest->full_name ?? '-',
                    $guest->identity_number ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}