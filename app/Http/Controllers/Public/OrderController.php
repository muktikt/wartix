<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderGuest;
use App\Models\OrderCustomField;
use App\Models\SalePhase;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Services\TelegramLinkTokenService;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id'           => 'required|exists:events,id',
            'sale_phase_id'      => 'required|exists:sale_phases,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'full_name'          => 'required|string|max:255',
            'phone_number'       => 'required|string|max:20',
            'email'              => 'required|email|max:255',
            'telegram_username'  => 'nullable|string|max:100',
        ]);

        $event = Event::with('customFields')->findOrFail($request->event_id);

        $phase    = SalePhase::where('event_id', $event->id)->find($request->sale_phase_id);
        $category = TicketCategory::where('event_id', $event->id)->find($request->ticket_category_id);

        if (!$phase || !$category) {
            return back()->withInput()->withErrors([
                'ticket_category_id' => 'Sale phase atau kategori tidak valid untuk event ini.',
            ]);
        }

        if ($phase->status !== 'open' && $event->status !== 'ongoing') {
            return back()->withInput()->withErrors([
                'sale_phase_id' => 'Sale phase ini belum dibuka atau sudah ditutup.',
            ]);
        }

        $maxQty = min($event->max_ticket_per_order, $category->max_qty ?: $event->max_ticket_per_order);

        $rules = [
            'qty' => "required|integer|min:1|max:{$maxQty}",
        ];

        if ($event->identity_mode === 'nik_only') {
            $rules['identity_number'] = 'required|digits:16';
        }

        if ($event->platform_type === 'tiketcom') {
            $rules['title'] = 'required|in:Tuan,Nyonya,Nona';
        }

        $activeCustomFields = $event->customFields->where('is_active', true);

        foreach ($activeCustomFields as $field) {
            $rules["custom_fields.{$field->id}"] = $field->is_required
                ? 'required|string|max:1000'
                : 'nullable|string|max:1000';
        }

        $request->validate($rules);

        $qty = (int) $request->qty;

        if ($category->slot_limit !== null) {
            $soldCategory = Order::where('ticket_category_id', $category->id)
                ->whereNotIn('order_status', ['failed', 'cancelled'])
                ->sum('qty');

            if ($soldCategory + $qty > $category->slot_limit) {
                return back()->withInput()->withErrors([
                    'ticket_category_id' => 'Slot untuk kategori ini sudah penuh.',
                ]);
            }
        }

        if ($phase->slot_limit !== null) {
            $soldPhase = Order::where('sale_phase_id', $phase->id)
                ->whereNotIn('order_status', ['failed', 'cancelled'])
                ->sum('qty');

            if ($soldPhase + $qty > $phase->slot_limit) {
                return back()->withInput()->withErrors([
                    'sale_phase_id' => 'Slot untuk sale phase ini sudah penuh.',
                ]);
            }
        }

        $serviceFeeTotal  = $category->fee_per_ticket * $qty;
        $ticketPriceTotal = 0;
        $grandTotal       = 0;

        if ($category->payment_mode === 'service_fee_only') {
            $grandTotal = $serviceFeeTotal;
        } elseif ($category->payment_mode === 'full_payment') {
            $ticketPriceTotal = $category->ticket_price * $qty;
            $grandTotal       = $serviceFeeTotal + $ticketPriceTotal;
        } elseif ($category->payment_mode === 'custom_payment') {
            $grandTotal = $category->custom_payment_amount ?? $serviceFeeTotal;
        }

        $linkToken = (new TelegramLinkTokenService())->generate($event);

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
            'telegram_link_token' => $linkToken,
            'service_fee_total'   => $serviceFeeTotal,
            'ticket_price_total'  => $ticketPriceTotal,
            'admin_fee'           => 0,
            'grand_total'         => $grandTotal,
            'payment_mode'        => $category->payment_mode,
            'payment_status'      => 'unpaid',
            'order_status'        => 'pending_link',
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

        foreach ($activeCustomFields as $field) {
            $value = $request->input("custom_fields.{$field->id}");
            if ($value !== null && $value !== '') {
                OrderCustomField::create([
                    'order_id'        => $order->id,
                    'custom_field_id' => $field->id,
                    'value'           => $value,
                ]);
            }
        }

        // Invalidate cache agar slot update real-time
        Cache::forget('active_events');
        Cache::forget('home_stats');

        return redirect()->route('order.success', $order->order_code);
    }

    public function success(string $orderCode)
    {
        $order = Order::withoutGlobalScope(\App\Models\Scopes\HideUnlinkedOrdersScope::class)
            ->where('order_code', $orderCode)
            ->with(['event', 'salePhase', 'ticketCategory', 'guests'])
            ->firstOrFail();

        $telegramBotUsername = \App\Models\Setting::get('telegram_bot_username', '');
        $telegramLinkUrl     = null;

        if ($telegramBotUsername && $order->telegram_link_token && $order->order_status === 'pending_link') {
            $telegramLinkUrl = "https://t.me/{$telegramBotUsername}?start={$order->telegram_link_token}";
        }

        return view('public.order-success', compact('order', 'telegramLinkUrl'));
    }
}
