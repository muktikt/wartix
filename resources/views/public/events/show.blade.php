@extends('layouts.app')
@section('title', $event->title.' — Wartix')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="grid md:grid-cols-3 gap-8">

        {{-- Left --}}
        <div class="md:col-span-2 space-y-6">
            {{-- Banner --}}
            <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-900 to-purple-900 aspect-video flex items-center justify-center">
                @if($event->banner_image)
                    <img src="{{ asset('storage/'.$event->banner_image) }}" class="w-full h-full object-cover" alt="{{ $event->title }}">
                @else
                    <span class="text-white/50 text-lg font-medium">{{ $event->title }}</span>
                @endif
            </div>

            {{-- Info --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $event->title }}</h1>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium
                        {{ $event->status === 'ongoing' ? 'bg-green-50 text-green-700' : 'bg-indigo-50 text-indigo-700' }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ $event->venue }}, {{ $event->city }}
                    </div>
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $event->event_date->format('d M Y') }}
                    </div>
                </div>
                @if($totalSlots !== null)
                <div class="mb-4 p-3 bg-gradient-to-br from-indigo-50 to-indigo-100/40 border border-indigo-100/60 rounded-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider block">Slot Tersedia</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-bold text-indigo-900">{{ $availableSlots }}</span>
                            <span class="text-xs font-medium text-indigo-400">/ {{ $totalSlots }} tiket</span>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold
                        {{ $availableSlots > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $availableSlots > 0 ? 'bg-emerald-500' : 'bg-rose-500' }} animate-pulse"></span>
                        {{ $availableSlots > 0 ? 'Tersedia' : 'Penuh' }}
                    </div>
                </div>
                @endif
                @if(request('debug'))
                <div class="mt-3 text-xs text-gray-500 bg-gray-50 p-2 rounded">
                    <pre class="whitespace-pre-wrap text-xs">{{ json_encode(array_merge($event->toArray(), ['totalSlots' => $totalSlots, 'availableSlots' => $availableSlots]), JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
                @if($event->description)
                <p class="text-sm text-gray-500 leading-relaxed">{{ $event->description }}</p>
                @endif
            </div>

            {{-- Seatplan --}}
            @if($event->seatplan_image)
            <div class="bg-white border border-gray-100 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Denah Tempat Duduk</h3>
                <img src="{{ asset('storage/'.$event->seatplan_image) }}" class="w-full rounded-xl" alt="Seatplan">
            </div>
            @endif

            {{-- Phases & Categories --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Sale Phase & Kategori</h3>

                {{-- Phase names --}}
                @if($event->salePhases->count())
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($event->salePhases as $phase)
                    <span class="text-xs bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-medium">
                        {{ $phase->name }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Categories & fee --}}
                <div class="space-y-2">
                    @foreach($event->ticketCategories as $cat)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm font-medium text-gray-900">{{ $cat->name }}</span>
                        <span class="text-sm font-semibold text-indigo-600">
                            Rp {{ number_format($cat->fee_per_ticket) }}/tiket
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right — Order Form --}}
        <div class="md:col-span-1">
            <div class="sticky top-20">
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Form Order</h3>

                    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-xs font-semibold text-red-800">Ada kesalahan pengisian form:</h3>
                                    <ul class="mt-1 list-disc list-inside text-xs text-red-700 space-y-0.5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Sale Phase --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Sale Phase</label>
                            <select name="sale_phase_id" id="salePhaseSelect" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih sale phase</option>
                                @foreach($event->salePhases as $phase)
                                    @if($phase->slot_limit !== null)
                                        @if($phase->available_slots > 0)
                                            <option value="{{ $phase->id }}" {{ old('sale_phase_id') == $phase->id ? 'selected' : '' }}>
                                                {{ $phase->name }} (Sisa {{ $phase->available_slots }} slot)
                                            </option>
                                        @else
                                            <option value="{{ $phase->id }}" disabled class="text-gray-400">
                                                {{ $phase->name }} (Penuh)
                                            </option>
                                        @endif
                                    @else
                                        <option value="{{ $phase->id }}" {{ old('sale_phase_id') == $phase->id ? 'selected' : '' }}>
                                            {{ $phase->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Membership Code (only if any phase contains "membership") --}}
                        @if($event->salePhases->contains(fn($p) => str_contains(strtolower($p->name), 'membership')))
                        <div id="membershipCodeField" class="hidden mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Kode Membership <span class="text-red-500">*</span></label>
                            <input type="text" name="membership_code" id="membershipCodeInput" value="{{ old('membership_code') }}" placeholder="Masukkan kode membership Anda"
                                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        @endif

                        {{-- Ticket Category --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Kategori Tiket</label>
                            <select name="ticket_category_id" id="categorySelect" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih kategori</option>
                                @foreach($event->ticketCategories as $cat)
                                    @if($cat->slot_limit !== null)
                                        @if($cat->available_slots > 0)
                                            <option value="{{ $cat->id }}"
                                                data-fee="{{ $cat->fee_per_ticket }}"
                                                data-price="{{ $cat->ticket_price }}"
                                                data-mode="{{ $cat->payment_mode }}"
                                                {{ old('ticket_category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }} — Rp {{ number_format($cat->fee_per_ticket) }}/tiket (Sisa {{ $cat->available_slots }} slot)
                                            </option>
                                        @else
                                            <option value="{{ $cat->id }}" disabled class="text-gray-400"
                                                data-fee="{{ $cat->fee_per_ticket }}"
                                                data-price="{{ $cat->ticket_price }}"
                                                data-mode="{{ $cat->payment_mode }}">
                                                {{ $cat->name }} — Rp {{ number_format($cat->fee_per_ticket) }}/tiket (Penuh)
                                            </option>
                                        @endif
                                    @else
                                        <option value="{{ $cat->id }}"
                                            data-fee="{{ $cat->fee_per_ticket }}"
                                            data-price="{{ $cat->ticket_price }}"
                                            data-mode="{{ $cat->payment_mode }}"
                                            {{ old('ticket_category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }} — Rp {{ number_format($cat->fee_per_ticket) }}/tiket
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Qty --}}
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Jumlah Tiket</label>
                            <select name="qty" id="qtySelect" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @for($i = 1; $i <= $event->max_ticket_per_order; $i++)
                                <option value="{{ $i }}" {{ old('qty', 1) == $i ? 'selected' : '' }}>{{ $i }} Tiket</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Estimasi Fee --}}
                        <div id="feeEstimate" class="hidden mb-4 bg-indigo-50 rounded-xl p-3">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Fee Jasa</span>
                                <span id="feeDisplay">Rp 0</span>
                            </div>
                            <div id="ticketPriceRow" class="hidden flex justify-between text-xs text-gray-600 mb-1">
                                <span>Harga Tiket</span>
                                <span id="ticketPriceDisplay">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm font-semibold text-indigo-700 border-t border-indigo-100 pt-1 mt-1">
                                <span>Total</span>
                                <span id="totalDisplay">Rp 0</span>
                            </div>
                        </div>

                        <hr class="border-gray-100 mb-4">

                        {{-- Platform: Tiket.com --}}
                        @if($event->platform_type === 'tiketcom')

                        {{-- Sapaan --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Sapaan</label>
                            <div class="flex gap-2">
                                @foreach(['Tuan', 'Nyonya', 'Nona'] as $title)
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="title" value="{{ $title }}" class="sr-only peer" required
                                        {{ old('title') === $title ? 'checked' : '' }}>
                                    <div class="text-center text-xs border border-gray-200 rounded-lg py-2 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-colors">
                                        {{ $title }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        @endif

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Sesuai KTP"
                                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- No HP --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Nomor Ponsel</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="08xxxxxxxxxx"
                                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com"
                                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- NIK --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Nomor KTP / NIK</label>
                            <input type="text" name="identity_number" value="{{ old('identity_number') }}" placeholder="16 digit NIK"
                                maxlength="16" minlength="16"
                                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- Username Sosial Media --}}
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Username Sosial Media (IG/TikTok/X/Threads)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">@</span>
                                <input type="text" name="telegram_username" value="{{ old('telegram_username') }}" placeholder="username"
                                    class="w-full text-sm border border-gray-200 rounded-xl pl-7 pr-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Masukkan salah satu username sosial media Anda (Instagram, TikTok, X, Threads, dll)</p>
                        </div>

                        {{-- Custom Fields --}}
                        @php $activeCustomFields = $event->customFields->where('is_active', true); @endphp
                        @if($activeCustomFields->count())
                        <hr class="border-gray-100 mb-4">
                        @foreach($activeCustomFields as $field)
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                {{ $field->label }} @if($field->is_required)<span class="text-red-500">*</span>@endif
                            </label>
                            @if($field->field_type === 'select')
                                <select name="custom_fields[{{ $field->id }}]" {{ $field->is_required ? 'required' : '' }}
                                    class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Pilih {{ $field->label }}</option>
                                    @foreach(($field->options ?? []) as $opt)
                                    <option value="{{ $opt }}" {{ old("custom_fields.{$field->id}") === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            @elseif($field->field_type === 'textarea')
                                <textarea name="custom_fields[{{ $field->id }}]" rows="3" {{ $field->is_required ? 'required' : '' }}
                                    class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old("custom_fields.{$field->id}") }}</textarea>
                            @else
                                <input type="{{ $field->field_type === 'password' ? 'password' : ($field->field_type === 'number' ? 'number' : 'text') }}"
                                    name="custom_fields[{{ $field->id }}]" value="{{ old("custom_fields.{$field->id}") }}" {{ $field->is_required ? 'required' : '' }}
                                    class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @endif
                        </div>
                        @endforeach
                        @endif

                        {{-- Guest NIK (multi guest) --}}
                        @if($event->guest_enabled && $event->guest_mode === 'multi_guest')
                        <div id="guestFields" class="hidden mb-4">
                            <hr class="border-gray-100 mb-4">
                            <p class="text-xs font-semibold text-gray-700 mb-3">Data Guest Tambahan</p>
                            <div id="guestContainer" class="space-y-2"></div>
                        </div>
                        @endif

                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-3 rounded-xl transition-colors">
                            Submit Order
                        </button>

                        <p class="text-xs text-gray-400 text-center mt-3">
                            Dengan submit, kamu menyetujui syarat & ketentuan Wartix
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Terms & Conditions Modal --}}
<div id="tcModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); opacity: 0; pointer-events: none; transition: opacity 0.3s ease;">
    <div style="background-color: #ffffff; border-radius: 1rem; max-width: 24rem; width: 100%; padding: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); transition: transform 0.3s ease;">
        <div style="display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; margin: 0 auto 1rem auto; background-color: #fef3c7; border-radius: 50%; color: #d97706;">
            <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 style="text-align: center; font-size: 0.875rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem; font-family: sans-serif;">Pemberitahuan Penting</h3>
        <p style="text-align: center; font-size: 0.75rem; color: #6b7280; line-height: 1.5; margin-bottom: 1.5rem; font-family: sans-serif;">
            Harap membaca deskripsi event terlebih dahulu karena di dalamnya terdapat Syarat & Ketentuan (Terms and Conditions) sebelum melakukan pemesanan.
        </p>
        <button id="closeTcModalBtn" style="width: 100%; background-color: #4f46e5; border: none; color: #ffffff; font-size: 0.75rem; font-weight: 600; padding: 0.625rem 0; border-radius: 0.75rem; cursor: pointer; transition: background-color 0.2s ease; font-family: sans-serif;">
            Oke, Saya Mengerti
        </button>
    </div>
</div>

<script>
const categorySelect = document.getElementById('categorySelect');
const qtySelect      = document.getElementById('qtySelect');
const feeEstimate    = document.getElementById('feeEstimate');
const feeDisplay     = document.getElementById('feeDisplay');
const totalDisplay   = document.getElementById('totalDisplay');
const ticketPriceRow = document.getElementById('ticketPriceRow');
const ticketPriceDisp= document.getElementById('ticketPriceDisplay');
const salePhaseSelect = document.getElementById('salePhaseSelect');
const membershipField = document.getElementById('membershipCodeField');
const membershipInput = document.getElementById('membershipCodeInput');
const oldGuestNiks = @json(collect(($event->max_ticket_per_order ?? 0) >= 2 ? range(2, $event->max_ticket_per_order) : [])->mapWithKeys(fn($i) => ["guest_nik_$i" => old("guest_nik_$i")]));

function formatRp(num) {
    return 'Rp ' + num.toLocaleString('id-ID');
}

function updateEstimate() {
    const opt  = categorySelect.options[categorySelect.selectedIndex];
    const qty  = parseInt(qtySelect.value) || 1;
    const fee  = parseInt(opt?.dataset?.fee || 0);
    const price= parseInt(opt?.dataset?.price || 0);
    const mode = opt?.dataset?.mode || '';

    if (!fee && !price) { feeEstimate.classList.add('hidden'); return; }

    feeEstimate.classList.remove('hidden');
    const totalFee   = fee * qty;
    const totalPrice = price * qty;
    let grandTotal   = totalFee;

    feeDisplay.textContent = formatRp(totalFee);

    if (mode === 'full_payment' && price > 0) {
        ticketPriceRow.classList.remove('hidden');
        ticketPriceDisp.textContent = formatRp(totalPrice);
        grandTotal = totalFee + totalPrice;
    } else {
        ticketPriceRow.classList.add('hidden');
    }

    totalDisplay.textContent = formatRp(grandTotal);
}

function updateMembershipVisibility() {
    if (!salePhaseSelect || !membershipField || !membershipInput) return;
    const selectedText = salePhaseSelect.options[salePhaseSelect.selectedIndex]?.text || '';
    if (selectedText.toLowerCase().includes('membership')) {
        membershipField.classList.remove('hidden');
        membershipInput.required = true;
    } else {
        membershipField.classList.add('hidden');
        membershipInput.required = false;
        membershipInput.value = '';
    }
}

categorySelect.addEventListener('change', updateEstimate);
qtySelect.addEventListener('change', function() {
    updateEstimate();
    updateGuestFields();
});
if (salePhaseSelect) {
    salePhaseSelect.addEventListener('change', updateMembershipVisibility);
}

// Guest fields
function updateGuestFields() {
    const guestDiv = document.getElementById('guestFields');
    const container= document.getElementById('guestContainer');
    if (!guestDiv || !container) return;

    const qty = parseInt(qtySelect.value) || 1;
    container.innerHTML = '';

    if (qty > 1) {
        guestDiv.classList.remove('hidden');
        for (let i = 2; i <= qty; i++) {
            const oldVal = oldGuestNiks[`guest_nik_${i}`] || '';
            container.innerHTML += `
            <div>
                <label class="block text-xs text-gray-600 mb-1">Tiket ${i} — Nomor KTP / NIK</label>
                <input type="text" name="guest_nik_${i}" value="${oldVal}" placeholder="16 digit NIK" maxlength="16" minlength="16"
                    class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
            </div>`;
        }
    } else {
        guestDiv.classList.add('hidden');
    }
}

// Initialize on page load
updateEstimate();
updateGuestFields();
updateMembershipVisibility();

// Show T&C Modal on load
window.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('tcModal');
    if (!modal) return;
    const modalContent = modal.querySelector('div');
    const btn = document.getElementById('closeTcModalBtn');

    // Show modal and disable body scroll
    setTimeout(() => {
        modal.style.opacity = '1';
        modal.style.pointerEvents = 'auto';
        modalContent.style.transform = 'scale(1)';
        document.body.style.overflow = 'hidden';
    }, 100);

    // Close function
    const closeModal = () => {
        modal.style.opacity = '0';
        modal.style.pointerEvents = 'none';
        modalContent.style.transform = 'scale(0.95)';
        document.body.style.overflow = '';
    };

    btn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
});
</script>
@endsection
