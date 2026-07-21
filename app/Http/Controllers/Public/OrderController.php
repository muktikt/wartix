<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderGuest;
use App\Models\OrderCustomField;
use App\Models\OrderCategoryChoice;
use App\Models\SalePhase;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramLinkTokenService;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // --- Validasi awal ---
        $request->validate([
            'event_id'                              => 'required|exists:events,id',
            'sale_phase_id'                         => 'required|exists:sale_phases,id',
            'category_choices'                      => 'required|array|min:1',
            'category_choices.*.ticket_category_id' => 'nullable|exists:ticket_categories,id',
            'category_choices.*.priority'           => 'required|integer|min:1',
            'full_name'                             => 'required|string|max:255',
            'phone_number'                          => 'required|string|max:20',
            'email'                                 => 'required|email|max:255',
            'telegram_username'                     => 'nullable|string|max:100',
            'identity_number'                       => 'nullable|string|max:16',
        ]);

        $event = Event::with('customFields')->findOrFail($request->event_id);

        // --- Cek status event ---
        if (in_array($event->status, ['finished', 'slot_penuh'])) {
            return back()->withInput()->withErrors([
                'event_id' => 'Pendaftaran untuk event ini sudah ditutup.',
            ]);
        }

        // --- Cek sale phase ---
        $phase = SalePhase::where('event_id', $event->id)->find($request->sale_phase_id);

        if (!$phase) {
            return back()->withInput()->withErrors([
                'sale_phase_id' => 'Sale phase tidak valid untuk event ini.',
            ]);
        }

        if ($phase->status === 'closed') {
            return back()->withInput()->withErrors([
                'sale_phase_id' => 'Sale phase ini sudah ditutup.',
            ]);
        }

        // --- Filter dan urutkan pilihan kategori ---
        $categoryChoices = collect($request->category_choices)
            ->filter(fn ($choice) => !empty($choice['ticket_category_id']))
            ->sortBy('priority')
            ->values();

        if ($categoryChoices->isEmpty()) {
            return back()->withInput()->withErrors([
                'category_choices' => 'Silakan pilih minimal satu kategori tiket.',
            ]);
        }

        // --- Kategori UTAMA ---
        $primaryCategoryId = $categoryChoices->first()['ticket_category_id'];
        $category = TicketCategory::where('event_id', $event->id)->find($primaryCategoryId);

        if (!$category) {
            return back()->withInput()->withErrors([
                'category_choices' => 'Kategori utama tidak valid untuk event ini.',
            ]);
        }

        // --- Validasi kategori cadangan ---
        foreach ($categoryChoices->skip(1) as $choice) {
            if (!TicketCategory::where('event_id', $event->id)->where('id', $choice['ticket_category_id'])->exists()) {
                return back()->withInput()->withErrors([
                    'category_choices' => 'Salah satu kategori cadangan tidak valid untuk event ini.',
                ]);
            }
        }

        // --- Validasi tambahan berdasarkan event setting ---
        $maxQty = min($event->max_ticket_per_order, $category->max_qty ?: $event->max_ticket_per_order);

        $rules = [
            'qty' => "required|integer|min:1|max:{$maxQty}",
        ];

        if ($event->identity_mode === 'nik_only') {
            $rules['identity_number'] = 'required|digits:16';
        }

        if (str_contains(strtolower($phase->name ?? ''), 'membership')) {
            $rules['membership_code'] = 'required|string|max:255';
        }

        $activeCustomFields = $event->customFields->where('is_active', true);

        foreach ($activeCustomFields as $field) {
            $rules["custom_fields.{$field->id}"] = $field->is_required
                ? 'required|string|max:1000'
                : 'nullable|string|max:1000';
        }

        $request->validate($rules);

        $qty = (int) $request->qty;

        // --- Cek slot event ---
        $availableSlots = $event->resolved_available_slots;
        if ($availableSlots !== null && $qty > $availableSlots) {
            return back()->withInput()->withErrors([
                'qty' => "Slot tidak mencukupi. Sisa slot yang tersedia adalah {$availableSlots}.",
            ]);
        }

        // --- Cek slot kategori ---
        if ($category->slot_limit !== null) {
            $soldCategory = Order::withoutGlobalScopes()
                ->where('ticket_category_id', $category->id)
                ->whereNotIn('order_status', ['failed', 'cancelled'])
                ->count();

            if ($soldCategory + $qty > $category->slot_limit) {
                return back()->withInput()->withErrors([
                    'category_choices' => 'Slot untuk kategori utama sudah penuh atau tidak mencukupi.',
                ]);
            }
        }

        // --- Cek slot phase ---
        if ($phase->slot_limit !== null) {
            $soldPhase = Order::withoutGlobalScopes()
                ->where('sale_phase_id', $phase->id)
                ->whereNotIn('order_status', ['failed', 'cancelled'])
                ->count();

            if ($soldPhase + $qty > $phase->slot_limit) {
                return back()->withInput()->withErrors([
                    'sale_phase_id' => 'Slot untuk sale phase ini sudah penuh atau tidak mencukupi.',
                ]);
            }
        }

        // --- Hitung fee ---
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

        // --- Simpan order dalam database transaction ---
        try {
            $linkToken = (new TelegramLinkTokenService())->generate($event);

            $order = Order::create([
                'order_code'          => 'WRTX-' . date('Y') . '-' . strtoupper(Str::random(6)),
                'event_id'            => $event->id,
                'sale_phase_id'       => $phase->id,
                'ticket_category_id'  => $category->id,
                'qty'                 => $qty,
                'title'               => $request->title ?? null,
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
                'membership_code'     => $request->membership_code ?? null,
            ]);

            // Simpan semua pilihan kategori ke pivot table
            foreach ($categoryChoices as $choice) {
                OrderCategoryChoice::create([
                    'order_id'           => $order->id,
                    'ticket_category_id' => $choice['ticket_category_id'],
                    'priority'           => $choice['priority'],
                ]);
            }

            // Guest data
            if ($event->guest_enabled && $event->guest_mode === 'multi_guest' && $qty > 1) {
                OrderGuest::create([
                    'order_id'        => $order->id,
                    'ticket_position' => 1,
                    'guest_type'      => 'main_buyer',
                    'title'           => $request->title ?? null,
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
                            'title'           => ($event->same_title_for_guest ? ($request->title ?? null) : null),
                            'full_name'       => null,
                            'identity_number' => $guestNik,
                        ]);
                    }
                }
            }

            // Custom fields
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

            Cache::forget('active_events');
            Cache::forget('home_stats');

            // Notifikasi admin (non-blocking)
            try {
                \App\Models\AdminNotification::notifyNewOrder($order->load('event'));
            } catch (\Throwable $e) {
                Log::warning('AdminNotification gagal dibuat: ' . $e->getMessage());
            }

            return redirect()->route('order.success', $order->order_code);

        } catch (\Throwable $e) {
            Log::error('Order store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['_token']),
            ]);

            return back()->withInput()->withErrors([
                'email' => 'Terjadi kesalahan saat memproses order. Silakan coba lagi. (' . class_basename($e) . ')',
            ]);
        }
    }

    public function success(string $orderCode)
    {
        $order = Order::withoutGlobalScope(\App\Models\Scopes\HideUnlinkedOrdersScope::class)
            ->where('order_code', $orderCode)
            ->with(['event', 'salePhase', 'ticketCategory', 'guests', 'categoryChoices.ticketCategory'])
            ->firstOrFail();

        $telegramBotUsername = \App\Models\Setting::get('telegram_bot_username', '');
        $telegramLinkUrl     = null;

        if ($telegramBotUsername && $order->telegram_link_token && $order->order_status === 'pending_link') {
            $telegramLinkUrl = "https://t.me/{$telegramBotUsername}?start={$order->telegram_link_token}";
        }

        return view('public.order-success', compact('order', 'telegramLinkUrl'));
    }
}