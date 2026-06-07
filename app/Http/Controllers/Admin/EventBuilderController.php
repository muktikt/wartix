<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SalePhase;
use App\Models\TicketCategory;
use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventBuilderController extends Controller
{
    public function create()
    {
        return view('admin.events.builder');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'artist_name'         => 'required|string|max:255',
            'venue'               => 'required|string|max:255',
            'city'                => 'required|string|max:255',
            'event_type'          => 'required|string|max:100',
            'event_date'          => 'required|date',
            'platform_type'       => 'required|in:tiketcom,loket,yesplis,custom',
            'status'              => 'required|in:upcoming,ongoing,finished',
            'banner_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seatplan_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'max_ticket_per_order'=> 'required|integer|min:1|max:10',
            'phases'              => 'required|array|min:1',
            'phases.*.name'       => 'required|string|max:100',
            'categories'          => 'required|array|min:1',
            'categories.*.name'   => 'required|string|max:100',
            'categories.*.fee_per_ticket' => 'required|integer|min:0',
        ]);

        $bannerPath   = null;
        $seatplanPath = null;

        if ($request->hasFile('banner_image')) {
            $bannerPath = $request->file('banner_image')->store('banners', 'public');
        }

        if ($request->hasFile('seatplan_image')) {
            $seatplanPath = $request->file('seatplan_image')->store('seatplans', 'public');
        }

        $event = Event::create([
            'title'                        => $request->title,
            'slug'                         => Str::slug($request->title).'-'.Str::random(4),
            'artist_name'                  => $request->artist_name,
            'banner_image'                 => $bannerPath,
            'seatplan_image'               => $seatplanPath,
            'description'                  => $request->description,
            'venue'                        => $request->venue,
            'city'                         => $request->city,
            'event_type'                   => $request->event_type,
            'event_date'                   => $request->event_date,
            'status'                       => $request->status,
            'platform_type'                => $request->platform_type,
            'max_ticket_per_order'         => $request->max_ticket_per_order,
            'checkout_type'                => $request->checkout_type ?? 'managed_checkout',
            'guest_enabled'                => $request->boolean('guest_enabled'),
            'guest_mode'                   => $request->guest_mode ?? 'single_buyer',
            'guest_identity_only'          => $request->boolean('guest_identity_only', true),
            'same_title_for_guest'         => $request->boolean('same_title_for_guest', true),
            'require_unique_identity_number' => $request->boolean('require_unique_identity_number', true),
            'identity_mode'                => $request->identity_mode ?? 'nik_only',
            'telegram_group_link'          => $request->telegram_group_link,
            'slot_availability'            => $request->slot_availability,
        ]);

        // Simpan sale phases
        foreach ($request->phases as $i => $phase) {
            SalePhase::create([
                'event_id'   => $event->id,
                'name'       => $phase['name'],
                'start_time' => $phase['start_time'] ?? null,
                'end_time'   => $phase['end_time'] ?? null,
                'status'     => $phase['status'] ?? 'upcoming',
                'slot_limit' => $phase['slot_limit'] ?? null,
                'description'=> $phase['description'] ?? null,
                'sort_order' => $i,
            ]);
        }

        // Simpan ticket categories
        foreach ($request->categories as $i => $cat) {
            TicketCategory::create([
                'event_id'             => $event->id,
                'name'                 => $cat['name'],
                'fee_per_ticket'       => $cat['fee_per_ticket'],
                'ticket_price'         => $cat['ticket_price'] ?? 0,
                'payment_mode'         => $cat['payment_mode'] ?? 'service_fee_only',
                'custom_payment_amount'=> $cat['custom_payment_amount'] ?? null,
                'max_qty'              => $cat['max_qty'] ?? 4,
                'slot_limit'           => $cat['slot_limit'] ?? null,
                'payment_timeout'      => $cat['payment_timeout'] ?? 10,
                'is_active'            => true,
                'sort_order'           => $i,
            ]);
        }

        // Simpan custom fields jika ada
        if ($request->has('custom_fields')) {
            foreach ($request->custom_fields as $i => $field) {
                CustomField::create([
                    'event_id'   => $event->id,
                    'label'      => $field['label'],
                    'field_name' => Str::slug($field['label'], '_'),
                    'field_type' => $field['field_type'] ?? 'text',
                    'options'    => $field['options'] ?? null,
                    'is_required'=> $field['is_required'] ?? false,
                    'is_active'  => true,
                    'sort_order' => $i,
                ]);
            }
        }

        // Trigger n8n announcement
        if ($event->status === 'ongoing' || $event->status === 'upcoming') {
            dispatch(new \App\Jobs\TriggerN8nWebhook([
                'event_type'  => 'event_created',
                'event_id'    => $event->id,
                'event_title' => $event->title,
                'event_slug'  => $event->slug,
            ]));
        }

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event berhasil dibuat dan dipublikasikan!');
    }

    public function edit(Event $event)
    {
        $event->load(['salePhases', 'ticketCategories', 'customFields']);
        return view('admin.events.builder', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'artist_name'         => 'required|string|max:255',
            'venue'               => 'required|string|max:255',
            'city'                => 'required|string|max:255',
            'event_type'          => 'required|string|max:100',
            'event_date'          => 'required|date',
            'platform_type'       => 'required|in:tiketcom,loket,yesplis,custom',
            'status'              => 'required|in:upcoming,ongoing,finished',
            'banner_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seatplan_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'max_ticket_per_order'=> 'required|integer|min:1|max:10',
            'phases'              => 'required|array|min:1',
            'categories'          => 'required|array|min:1',
        ]);

        if ($request->hasFile('banner_image')) {
            $event->banner_image = $request->file('banner_image')->store('banners', 'public');
        }

        if ($request->hasFile('seatplan_image')) {
            $event->seatplan_image = $request->file('seatplan_image')->store('seatplans', 'public');
        }

        $event->update([
            'title'                          => $request->title,
            'artist_name'                    => $request->artist_name,
            'description'                    => $request->description,
            'venue'                          => $request->venue,
            'city'                           => $request->city,
            'event_type'                     => $request->event_type,
            'event_date'                     => $request->event_date,
            'status'                         => $request->status,
            'platform_type'                  => $request->platform_type,
            'max_ticket_per_order'           => $request->max_ticket_per_order,
            'guest_enabled'                  => $request->boolean('guest_enabled'),
            'guest_mode'                     => $request->guest_mode ?? 'single_buyer',
            'guest_identity_only'            => $request->boolean('guest_identity_only', true),
            'same_title_for_guest'           => $request->boolean('same_title_for_guest', true),
            'require_unique_identity_number' => $request->boolean('require_unique_identity_number', true),
            'identity_mode'                  => $request->identity_mode ?? 'nik_only',
            'telegram_group_link'            => $request->telegram_group_link,
            'slot_availability'              => $request->slot_availability,
        ]);

        // Update phases — hapus lama, buat baru
        $event->salePhases()->delete();
        foreach ($request->phases as $i => $phase) {
            SalePhase::create([
                'event_id'   => $event->id,
                'name'       => $phase['name'],
                'start_time' => $phase['start_time'] ?? null,
                'end_time'   => $phase['end_time'] ?? null,
                'status'     => $phase['status'] ?? 'upcoming',
                'slot_limit' => $phase['slot_limit'] ?? null,
                'description'=> $phase['description'] ?? null,
                'sort_order' => $i,
            ]);
        }

        // Update categories
        $event->ticketCategories()->delete();
        foreach ($request->categories as $i => $cat) {
            TicketCategory::create([
                'event_id'             => $event->id,
                'name'                 => $cat['name'],
                'fee_per_ticket'       => $cat['fee_per_ticket'],
                'ticket_price'         => $cat['ticket_price'] ?? 0,
                'payment_mode'         => $cat['payment_mode'] ?? 'service_fee_only',
                'custom_payment_amount'=> $cat['custom_payment_amount'] ?? null,
                'max_qty'              => $cat['max_qty'] ?? 4,
                'slot_limit'           => $cat['slot_limit'] ?? null,
                'payment_timeout'      => $cat['payment_timeout'] ?? 10,
                'is_active'            => true,
                'sort_order'           => $i,
            ]);
        }

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event berhasil diupdate!');
    }
}