<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderGuest;
use App\Services\MaskService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function orders(Request $request)
    {
        $orders = $this->buildOrderQuery($request)
            ->latest()
            ->get();

        return $this->downloadCsv(
            'wartix-orders-' . now()->format('Ymd-His') . '.csv',
            ['Order Code', 'Pemesan', 'Email', 'Event', 'Kategori', 'Qty', 'Order Status', 'Payment Status', 'Tanggal'],
            $orders->map(function (Order $order) {
                return [
                    $order->order_code,
                    $order->full_name,
                    MaskService::email($order->email),
                    $order->event->title ?? '-',
                    ($order->ticketCategory->name ?? '-') . ' x' . $order->qty,
                    $order->qty,
                    ucfirst($order->order_status),
                    ucfirst($order->payment_status),
                    $order->created_at?->format('d M Y H:i') ?? '-',
                ];
            })->all()
        );
    }

    public function reports(Request $request)
    {
        $orders = $this->buildReportQuery($request)
            ->latest()
            ->get();

        return $this->downloadCsv(
            'wartix-reports-' . now()->format('Ymd-His') . '.csv',
            ['Order Code', 'Pemesan', 'Email', 'Event', 'Kategori', 'Qty', 'Total', 'Status', 'Tanggal'],
            $orders->map(function (Order $order) {
                return [
                    $order->order_code,
                    $order->full_name,
                    MaskService::email($order->email),
                    $order->event->title ?? '-',
                    ($order->ticketCategory->name ?? '-') . ' x' . $order->qty,
                    $order->qty,
                    'Rp ' . number_format((int) $order->grand_total),
                    ucfirst($order->order_status),
                    $order->created_at?->format('d M Y H:i') ?? '-',
                ];
            })->all()
        );
    }

    public function guests(Request $request)
    {
        $guests = OrderGuest::with(['order.event', 'order.salePhase', 'order.ticketCategory'])
            ->when($request->event_id, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('event_id', $request->event_id)))
            ->when($request->order_status, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('order_status', $request->order_status)))
            ->latest()
            ->get();

        return $this->downloadCsv(
            'wartix-guests-' . now()->format('Ymd-His') . '.csv',
            ['Order Code', 'Event', 'Buyer', 'Ticket Position', 'NIK', 'Type', 'Tanggal'],
            $guests->map(function (OrderGuest $guest) {
                return [
                    $guest->order->order_code ?? '-',
                    $guest->order->event->title ?? '-',
                    MaskService::email($guest->order->email ?? ''),
                    $guest->ticket_position,
                    MaskService::nik($guest->identity_number ?? ''),
                    $guest->guest_type === 'main_buyer' ? 'Main' : 'Guest',
                    $guest->created_at?->format('d M Y H:i') ?? '-',
                ];
            })->all()
        );
    }

    private function buildOrderQuery(Request $request)
    {
        $query = Order::with(['event', 'salePhase', 'ticketCategory']);

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

        return $query;
    }

    private function buildReportQuery(Request $request)
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

        return $query;
    }

    private function downloadCsv(string $filename, array $headings, array $rows)
    {
        $callback = function () use ($headings, $rows) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }

            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $headings);

            foreach ($rows as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
