<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SalePhase;
use App\Models\TicketCategory;
use App\Models\CustomField;
use App\Models\Setting;
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
        $request->validate($this->rules());

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
            'guest_identity_only'          => $request->boolean('guest_identity_only'),
            'same_title_for_guest'         => $request->boolean('same_title_for_guest'),
            'require_unique_identity_number' => $request->boolean('require_unique_identity_number'),
            'identity_mode'                => $request->identity_mode ?? 'nik_only',
            'telegram_group_link'          => $request->telegram_group_link,
            'slot_availability'            => Setting::get('default_slot_availability', null),
        ]);

        $this->syncSalePhases($event, $request->phases);
        $this->syncTicketCategories($event, $request->categories, $request->payment_mode);
        $this->syncCustomFields($event, $request->custom_fields ?? []);

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
        $event->load(['salePhases', 'ticketCategories', 'customFields' => function ($q) {
            $q->where('is_active', true);
        }]);
        return view('admin.events.builder', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate($this->rules());

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
            'guest_identity_only'            => $request->boolean('guest_identity_only'),
            'same_title_for_guest'           => $request->boolean('same_title_for_guest'),
            'require_unique_identity_number' => $request->boolean('require_unique_identity_number'),
            'identity_mode'                  => $request->identity_mode ?? 'nik_only',
            'telegram_group_link'            => $request->telegram_group_link,
            'slot_availability'              => Setting::get('default_slot_availability', null),
        ]);

        // Catat phase/kategori yang admin coba hapus tapi sudah punya order,
        // supaya bisa diberi tahu (sync di bawah otomatis tidak akan menghapusnya).
        $submittedPhaseIds = collect($request->phases)->pluck('id')->filter()->map(fn ($id) => (int) $id);
        $lockedPhases = $event->salePhases()
            ->whereHas('orders')
            ->whereNotIn('id', $submittedPhaseIds)
            ->pluck('name');

        $submittedCategoryIds = collect($request->categories)->pluck('id')->filter()->map(fn ($id) => (int) $id);
        $lockedCategories = $event->ticketCategories()
            ->whereHas('orders')
            ->whereNotIn('id', $submittedCategoryIds)
            ->pluck('name');

        $this->syncSalePhases($event, $request->phases);
        $this->syncTicketCategories($event, $request->categories, $request->payment_mode);
        $this->syncCustomFields($event, $request->custom_fields ?? []);

        $message = 'Event berhasil diupdate!';
        $locked  = $lockedPhases->merge($lockedCategories);
        if ($locked->isNotEmpty()) {
            $message .= ' Catatan: ' . $locked->implode(', ') . ' tidak dihapus karena sudah punya order terkait.';
        }

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', $message);
    }

    private function rules(): array
    {
        return [
            'title'               => 'required|string|max:255',
            'artist_name'         => 'required|string|max:255',
            'venue'               => 'required|string|max:255',
            'city'                => 'required|string|max:255',
            'event_type'          => 'required|string|max:100',
            'event_date'          => 'required|date',
            'platform_type'       => 'required|in:tiketcom,loket,yesplis,custom',
            'status'              => 'required|in:upcoming,slot_penuh,ongoing,finished',
            'banner_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seatplan_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'max_ticket_per_order'=> 'required|integer|min:1|max:10',
            'phases'              => 'required|array|min:1',
            'phases.*.name'       => 'required|string|max:100',
            'categories'          => 'required|array|min:1',
            'categories.*.name'   => 'required|string|max:100',
            'categories.*.fee_per_ticket'        => 'required|integer|min:0',
            'payment_mode'                       => 'required|in:service_fee_only,full_payment,custom_payment',
            'categories.*.ticket_price'          => 'required_if:payment_mode,full_payment|nullable|integer|min:1',
            'categories.*.custom_payment_amount' => 'required_if:payment_mode,custom_payment|nullable|integer|min:1',
            'custom_fields'                    => 'nullable|array',
            'custom_fields.*.label'            => 'required_with:custom_fields|string|max:255',
            'custom_fields.*.field_type'       => 'required_with:custom_fields|in:text,password,number,textarea,select',
            'custom_fields.*.options'          => 'nullable|string|max:1000',
            'custom_fields.*.is_required'      => 'nullable|boolean',
        ];
    }

    private function syncSalePhases(Event $event, array $phasesInput): void
    {
        $keepIds = [];

        foreach ($phasesInput as $i => $phase) {
            $data = [
                'event_id'    => $event->id,
                'name'        => $phase['name'],
                'start_time'  => $phase['start_time'] ?? null,
                'end_time'    => $phase['end_time'] ?? null,
                'status'      => $phase['status'] ?? 'upcoming',
                'slot_limit'  => $phase['slot_limit'] ?? null,
                'description' => $phase['description'] ?? null,
                'sort_order'  => $i,
            ];

            $id       = $phase['id'] ?? null;
            $existing = $id ? $event->salePhases()->find($id) : null;

            if ($existing) {
                $existing->update($data);
                $keepIds[] = $existing->id;
            } else {
                $keepIds[] = SalePhase::create($data)->id;
            }
        }

        // Hanya hapus phase lama yang TIDAK disubmit lagi DAN belum punya order.
        $event->salePhases()
            ->whereNotIn('id', $keepIds)
            ->whereDoesntHave('orders')
            ->delete();
    }

    private function syncTicketCategories(Event $event, array $categoriesInput, string $paymentMode): void
    {
        $keepIds = [];

        foreach ($categoriesInput as $i => $cat) {
            $data = [
                'event_id'              => $event->id,
                'name'                  => $cat['name'],
                'fee_per_ticket'        => $cat['fee_per_ticket'],
                'ticket_price'          => $cat['ticket_price'] ?? 0,
                'payment_mode'          => $paymentMode,
                'custom_payment_amount' => $cat['custom_payment_amount'] ?? null,
                'max_qty'               => $cat['max_qty'] ?? 4,
                'slot_limit'            => $cat['slot_limit'] ?? null,
                'payment_timeout'       => $cat['payment_timeout'] ?? 10,
                'is_active'             => true,
                'sort_order'            => $i,
            ];

            $id       = $cat['id'] ?? null;
            $existing = $id ? $event->ticketCategories()->find($id) : null;

            if ($existing) {
                $existing->update($data);
                $keepIds[] = $existing->id;
            } else {
                $keepIds[] = TicketCategory::create($data)->id;
            }
        }

        $event->ticketCategories()
            ->whereNotIn('id', $keepIds)
            ->whereDoesntHave('orders')
            ->delete();
    }

    private function syncCustomFields(Event $event, array $fieldsInput): void
    {
        $keepIds = [];

        foreach ($fieldsInput as $i => $field) {
            if (empty($field['label'])) {
                continue;
            }

            $type    = $field['field_type'] ?? 'text';
            $options = null;

            if ($type === 'select' && !empty($field['options'])) {
                $options = array_values(array_filter(array_map('trim', explode(',', $field['options']))));
            }

            $data = [
                'event_id'    => $event->id,
                'label'       => $field['label'],
                'field_name'  => Str::slug($field['label'], '_'),
                'field_type'  => $type,
                'options'     => $options,
                'is_required' => !empty($field['is_required']),
                'is_active'   => true,
                'sort_order'  => $i,
            ];

            $id       = $field['id'] ?? null;
            $existing = $id ? $event->customFields()->find($id) : null;

            if ($existing) {
                $existing->update($data);
                $keepIds[] = $existing->id;
            } else {
                $keepIds[] = CustomField::create($data)->id;
            }
        }

        // Deactivate custom fields that have order answers (preserve data)
        $event->customFields()
            ->whereNotIn('id', $keepIds)
            ->whereHas('orderAnswers')
            ->update(['is_active' => false]);

        // Delete custom fields without any order answers
        $event->customFields()
            ->whereNotIn('id', $keepIds)
            ->whereDoesntHave('orderAnswers')
            ->delete();
    }
}