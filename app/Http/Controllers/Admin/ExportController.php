<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MaskService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function orders(Request $request)
    {
        $orders = $this->buildOrderQuery($request)
            ->with('guests')
            ->latest()
            ->get();

        // Determine max guest count for dynamic columns
        $maxGuests = $orders->max(fn (Order $o) => $o->guests->where('guest_type', 'additional_guest')->count());
        $maxGuests = max($maxGuests, 0);

        // Build headings
        $headings = ['Order Code', 'Pemesan', 'Email', 'Telepon', 'NIK', 'Event', 'Kategori', 'Qty', 'Order Status', 'Payment Status', 'Multi Guest', 'Tanggal'];
        for ($i = 1; $i <= $maxGuests; $i++) {
            $headings[] = "Guest NIK #{$i}";
        }

        return $this->downloadCsv(
            'wartix-orders-' . now()->format('Ymd-His') . '.csv',
            $headings,
            $orders->map(function (Order $order) use ($maxGuests) {
                $additionalGuests = $order->guests->where('guest_type', 'additional_guest')->values();
                $isMultiGuest = $additionalGuests->count() > 0;

                $row = [
                    $order->order_code,
                    $order->full_name,
                    $order->email,
                    $order->phone_number ? '="' . $order->phone_number . '"' : '',
                    $order->identity_number ? '="' . $order->identity_number . '"' : '',
                    $order->event->title ?? '-',
                    ($order->ticketCategory->name ?? '-') . ' x' . $order->qty,
                    $order->qty,
                    ucfirst($order->order_status),
                    ucfirst($order->payment_status),
                    $isMultiGuest ? 'Ya (' . $order->guests->count() . ' tiket)' : 'Tidak',
                    $order->created_at ? '="' . $order->created_at->format('d M Y H:i') . '"' : '-',
                ];

                // Append guest NIK columns
                for ($i = 0; $i < $maxGuests; $i++) {
                    $guest = $additionalGuests->get($i);
                    $row[] = ($guest && $guest->identity_number) ? '="' . $guest->identity_number . '"' : '';
                }

                return $row;
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
                    $order->email,
                    $order->event->title ?? '-',
                    ($order->ticketCategory->name ?? '-') . ' x' . $order->qty,
                    $order->qty,
                    'Rp ' . number_format((int) $order->grand_total),
                    ucfirst($order->order_status),
                    $order->created_at ? '="' . $order->created_at->format('d M Y H:i') . '"' : '-',
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
