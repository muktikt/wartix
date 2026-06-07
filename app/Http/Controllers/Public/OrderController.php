<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderGuest;
use App\Models\SalePhase;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $event    = Event::findOrFail($request->event_id);
        $phase    = SalePhase::findOrFail($request->sale_phase_id);
        $category = TicketCategory::findOrFail($request->ticket_category_id);

        $rules = [
            'event_id'           => 'required|exists:events,id',
            'sale_phase_id'      => 'required|exists:sale_phases,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'qty'                => 'required|integer|min:1|max:' . $event->max_ticket_per_order,
            'full_name'          => 'required|string|max:255',
            'phone_number'       => 'required|string|max:20',
            'email'              => 'required|email|max:255',
            'telegram_username'  => 'nullable|string|max:100',
        ];

        if ($event->identity_mode === 'nik_only') {
            $rules['identity_number'] = 'required|digits:16';
        }

        if ($event->platform_type === 'tiketcom') {
            $rules['title'] = 'required|in:Tuan,Nyonya,Nona';
        }

        $request->validate($rules);

        $qty = (int) $request->qty;

        $serviceFeeTotal  = $category->fee_per_ticket * $qty;
        $ticketPriceTotal = 0;
        $grandTotal       = 0;

        if ($category->payment_mode === 'service_fee_only') {
            $grandTotal = $serviceFeeTotal;
        } elseif ($category->payment_mode === 'full_payment') {
            $ticketPriceTotal = $category->ticket_price * $qty;
            $grandTotal       = $serviceFeeTotal + $ticketPriceTotal;
        } elseif ($category->payment_mode === 'custom_payment') {
            $grandTotal = $category->custom_payment_amount;
        }

        $order = Order::create([
            'order_code'          => 'WRTX-' . date('Y') . '-' . strtoupper(Str::random(6)),
            'event_id'            => $event->id,
            'sale_phase_id'       => $phase->id,
            'ticket_category_id'  => $category->id,
            'qty'                 => $qty,
            'title'               => $request->title,
            'full_name'           => $request->full_name,
            'phone_number'        => $request->phone_number,
            'email'               => $request->email,
            'identity_number'     => $request->identity_number,
            'telegram_username'   => $request->telegram_username,
            'service_fee_total'   => $serviceFeeTotal,
            'ticket_price_total'  => $ticketPriceTotal,
            'admin_fee'           => 0,
            'grand_total'         => $grandTotal,
            'payment_mode'        => $category->payment_mode,
            'payment_status'      => 'unpaid',
            'order_status'        => 'waiting',
        ]);

        if ($event->guest_enabled && $event->guest_mode === 'multi_guest' && $qty > 1) {
            OrderGuest::create([
                'order_id'        => $order->id,
                'ticket_position' => 1,
                'guest_type'      => 'main_buyer',
                'title'           => $request->title,
                'full_name'       => $request->full_name,
                'identity_number' => $request->identity_number,
            ]);

            for ($i = 2; $i <= $qty; $i++) {
                $guestNik = $request->input("guest_nik_{$i}");
                if ($guestNik) {
                    OrderGuest::create([
                        'order_id'        => $order->id,
                        'ticket_position' => $i,
                        'guest_type'      => 'additional_guest',
                        'title'           => $event->same_title_for_guest ? $request->title : null,
                        'full_name'       => null,
                        'identity_number' => $guestNik,
                    ]);
                }
            }
        }

        return redirect()->route('order.success', $order->order_code);
    }

    public function success(string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['event', 'salePhase', 'ticketCategory', 'guests'])
            ->firstOrFail();

        return view('public.order-success', compact('order'));
    }
}