@extends('layouts.admin')
@section('title', isset($event) ? 'Edit Event' : 'Add Event')
@section('page-title', isset($event) ? 'Edit Event' : 'Event Builder')

@section('content')
@php $isEdit = isset($event); @endphp

<form action="{{ $isEdit ? route('admin.events.builder.update', $event) : route('admin.events.builder.store') }}"
    method="POST" enctype="multipart/form-data" x-data="eventBuilder()">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-3 gap-5">

        {{-- MAIN --}}
        <div class="col-span-2 space-y-4">

            {{-- Step 1: Platform --}}
            <div class="bg-white border border-gray-100 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center">1</span>
                    Ticketing Platform
                </h3>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['tiketcom' => 'Tiket.com', 'loket' => 'Loket', 'yesplis' => 'Yesplis', 'custom' => 'Custom'] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="platform_type" value="{{ $val }}"
                            x-model="platform"
                            {{ ($isEdit ? $event->platform_type : old('platform_type', 'tiketcom')) === $val ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="border-2 rounded-xl p-3 text-center transition-all peer-checked:border-indigo-600 peer-checked:bg-indigo-50 border-gray-200 hover:border-gray-300">
                            <div class="text-sm font-medium text-gray-700 peer-checked:text-indigo-700">{{ $label }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Step 2: Event Info --}}
            <div class="bg-white border border-gray-100 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center">2</span>
                    Event Information
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Nama Event *</label>
                        <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required placeholder="THE WEEKND WORLD TOUR">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Nama Artis *</label>
                        <input type="text" name="artist_name" value="{{ old('artist_name', $event->artist_name ?? '') }}"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Jenis Event *</label>
                        <input type="text" name="event_type" value="{{ old('event_type', $event->event_type ?? '') }}"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Konser, Festival, Fanmeeting" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Venue *</label>
                        <input type="text" name="venue" value="{{ old('venue', $event->venue ?? '') }}"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Kota *</label>
                        <input type="text" name="city" value="{{ old('city', $event->city ?? '') }}"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Tanggal Event *</label>
                        <input type="datetime-local" name="event_date"
                            value="{{ old('event_date', isset($event) ? $event->event_date->format('Y-m-d\TH:i') : '') }}"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Status *</label>
                        <select name="status"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @foreach(['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'finished' => 'Finished'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $event->status ?? 'upcoming') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Max Tiket / Order</label>
                        <input type="number" name="max_ticket_per_order" min="1" max="10"
                            value="{{ old('max_ticket_per_order', $event->max_ticket_per_order ?? 4) }}"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $event->description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Banner Image</label>
                        <input type="file" name="banner_image" accept="image/jpg,image/jpeg,image/png,image/webp"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-400 mt-1">JPG/PNG/WebP, max 2MB</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Seatplan Image</label>
                        <input type="file" name="seatplan_image" accept="image/jpg,image/jpeg,image/png,image/webp"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-400 mt-1">JPG/PNG/WebP, max 2MB</p>
                    </div>
                </div>
            </div>

            {{-- Step 3: Sale Phases --}}
            <div class="bg-white border border-gray-100 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center">3</span>
                    Sale Phases
                </h3>
                <div class="space-y-3" id="phasesContainer">
                    @if($isEdit && $event->salePhases->count())
                        @foreach($event->salePhases as $i => $phase)
                        <div class="border border-gray-100 rounded-xl p-4 phase-item">
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Nama Phase *</label>
                                    <input type="hidden" name="phases[{{ $i }}][id]" value="{{ $phase->id }}">
                                    <input type="text" name="phases[{{ $i }}][name]" value="{{ $phase->name }}"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                                    <input type="datetime-local" name="phases[{{ $i }}][start_time]"
                                        value="{{ $phase->start_time?->format('Y-m-d\TH:i') }}"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">End Time</label>
                                    <input type="datetime-local" name="phases[{{ $i }}][end_time]"
                                        value="{{ $phase->end_time?->format('Y-m-d\TH:i') }}"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="border border-gray-100 rounded-xl p-4 phase-item">
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Nama Phase *</label>
                                <input type="text" name="phases[0][name]" placeholder="Artist Presale"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                                <input type="datetime-local" name="phases[0][start_time]"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">End Time</label>
                                <input type="datetime-local" name="phases[0][end_time]"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <button type="button" onclick="addPhase()"
                    class="mt-3 w-full border border-dashed border-indigo-300 text-indigo-600 text-xs py-2 rounded-xl hover:bg-indigo-50 transition-colors">
                    + Tambah Sale Phase
                </button>
            </div>

            {{-- Step 4: Categories & Fee --}}
            <div class="bg-white border border-gray-100 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center">4</span>
                    Categories & Fee
                    <span class="text-xs text-gray-400 font-normal ml-1">(berlaku untuk semua phase)</span>
                </h3>
                <div class="space-y-3" id="categoriesContainer">
                    @if($isEdit && $event->ticketCategories->count())
                        @foreach($event->ticketCategories as $i => $cat)
                        <div class="border border-gray-100 rounded-xl p-4">
                            <div class="grid grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Nama Kategori *</label>
                                    <input type="hidden" name="categories[{{ $i }}][id]" value="{{ $cat->id }}">
                                    <input type="text" name="categories[{{ $i }}][name]" value="{{ $cat->name }}"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Fee / Tiket (Rp) *</label>
                                    <input type="number" name="categories[{{ $i }}][fee_per_ticket]" value="{{ $cat->fee_per_ticket }}"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Payment Mode</label>
                                    <select name="categories[{{ $i }}][payment_mode]"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="service_fee_only" {{ $cat->payment_mode === 'service_fee_only' ? 'selected' : '' }}>Fee Only</option>
                                        <option value="full_payment" {{ $cat->payment_mode === 'full_payment' ? 'selected' : '' }}>Full Payment</option>
                                        <option value="custom_payment" {{ $cat->payment_mode === 'custom_payment' ? 'selected' : '' }}>Custom</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Max Qty</label>
                                    <input type="number" name="categories[{{ $i }}][max_qty]" value="{{ $cat->max_qty }}" min="1"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="border border-gray-100 rounded-xl p-4">
                        <div class="grid grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Nama Kategori *</label>
                                <input type="text" name="categories[0][name]" placeholder="CAT 1"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Fee / Tiket (Rp) *</label>
                                <input type="number" name="categories[0][fee_per_ticket]" placeholder="300000"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Payment Mode</label>
                                <select name="categories[0][payment_mode]"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="service_fee_only">Fee Only</option>
                                    <option value="full_payment">Full Payment</option>
                                    <option value="custom_payment">Custom</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Max Qty</label>
                                <input type="number" name="categories[0][max_qty]" value="4" min="1"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <button type="button" onclick="addCategory()"
                    class="mt-3 w-full border border-dashed border-indigo-300 text-indigo-600 text-xs py-2 rounded-xl hover:bg-indigo-50 transition-colors">
                    + Tambah Kategori
                </button>
            </div>
            {{-- Step 6: Custom Fields --}}
            <div class="bg-white border border-gray-100 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center">6</span>
                    Custom Fields
                    <span class="text-xs text-gray-400 font-normal ml-1">(opsional, data tambahan dari pembeli)</span>
                </h3>
                <div class="space-y-3" id="customFieldsContainer">
                    @if($isEdit && $event->customFields->count())
                        @foreach($event->customFields as $i => $field)
                        <div class="border border-gray-100 rounded-xl p-4 custom-field-item">
                            <input type="hidden" name="custom_fields[{{ $i }}][id]" value="{{ $field->id }}">
                            <div class="grid grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Label *</label>
                                    <input type="text" name="custom_fields[{{ $i }}][label]" value="{{ $field->label }}"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Tipe</label>
                                    <select name="custom_fields[{{ $i }}][field_type]"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        @foreach(['text','password','number','textarea','select'] as $type)
                                        <option value="{{ $type }}" {{ $field->field_type === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Options (pisah koma, khusus Select)</label>
                                    <input type="text" name="custom_fields[{{ $i }}][options]" value="{{ implode(',', $field->options ?? []) }}"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="flex items-end gap-2">
                                    <label class="flex items-center gap-1.5 text-xs text-gray-600">
                                        <input type="checkbox" name="custom_fields[{{ $i }}][is_required]" value="1" {{ $field->is_required ? 'checked' : '' }}
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                                        Wajib
                                    </label>
                                    <button type="button" onclick="this.closest('.custom-field-item').remove()"
                                        class="text-xs text-red-400 hover:text-red-600 ml-auto">Hapus</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" onclick="addCustomField()"
                    class="mt-3 w-full border border-dashed border-indigo-300 text-indigo-600 text-xs py-2 rounded-xl hover:bg-indigo-50 transition-colors">
                    + Tambah Custom Field
                </button>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-4">

            {{-- Step 5: Guest Settings --}}
            <div class="bg-white border border-gray-100 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center">5</span>
                    Form & Guest
                </h3>
                <div class="space-y-3">
                    <label class="flex items-center justify-between">
                        <span class="text-xs text-gray-700">Multi Guest</span>
                        <input type="hidden" name="guest_enabled" value="0">
                        <input type="checkbox" name="guest_enabled" value="1"
                            {{ old('guest_enabled', $event->guest_enabled ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    </label>
                    <label class="flex items-center justify-between">
                        <span class="text-xs text-gray-700">Guest Wajib Isi Identitas</span>
                        <input type="hidden" name="guest_identity_only" value="0">
                        <input type="checkbox" name="guest_identity_only" value="1"
                            {{ old('guest_identity_only', $event->guest_identity_only ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    </label>
                    <label class="flex items-center justify-between">
                        <span class="text-xs text-gray-700">Same Title for Guest</span>
                        <input type="hidden" name="same_title_for_guest" value="0">
                        <input type="checkbox" name="same_title_for_guest" value="1"
                            {{ old('same_title_for_guest', $event->same_title_for_guest ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    </label>
                    <label class="flex items-center justify-between">
                        <span class="text-xs text-gray-700">Unique NIK Required</span>
                        <input type="hidden" name="require_unique_identity_number" value="0">
                        <input type="checkbox" name="require_unique_identity_number" value="1"
                            {{ old('require_unique_identity_number', $event->require_unique_identity_number ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    </label>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Guest Mode</label>
                        <select name="guest_mode"
                            class="w-full text-xs border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="single_buyer" {{ old('guest_mode', $event->guest_mode ?? 'single_buyer') === 'single_buyer' ? 'selected' : '' }}>Single Buyer</option>
                            <option value="multi_guest" {{ old('guest_mode', $event->guest_mode ?? '') === 'multi_guest' ? 'selected' : '' }}>Multi Guest</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Telegram --}}
            <div class="bg-white border border-gray-100 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Telegram Group</h3>
                <input type="url" name="telegram_group_link"
                    value="{{ old('telegram_group_link', $event->telegram_group_link ?? '') }}"
                    placeholder="https://t.me/..."
                    class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- Submit --}}
            <div class="bg-white border border-gray-100 rounded-xl p-5">
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-3 rounded-xl transition-colors">
                    {{ $isEdit ? 'Update Event' : 'Publish Event' }}
                </button>
                @if($isEdit)
                <a href="{{ route('admin.events.show', $event) }}"
                    class="block text-center mt-2 text-xs text-gray-500 hover:text-gray-700">
                    Batal
                </a>
                @endif
            </div>
        </div>
    </div>
</form>

<script>
let phaseIndex   = {{ $isEdit ? $event->salePhases->count() : 1 }};
let categoryIndex= {{ $isEdit ? $event->ticketCategories->count() : 1 }};
let customFieldIndex = {{ $isEdit ? $event->customFields->count() : 0 }};

function addCustomField() {
    const container = document.getElementById('customFieldsContainer');
    const div = document.createElement('div');
    div.className = 'border border-gray-100 rounded-xl p-4 custom-field-item';
    div.innerHTML = `
        <div class="grid grid-cols-4 gap-3">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Label *</label>
                <input type="text" name="custom_fields[${customFieldIndex}][label]" placeholder="Username Tiket.com"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Tipe</label>
                <select name="custom_fields[${customFieldIndex}][field_type]"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="text">Text</option>
                    <option value="password">Password</option>
                    <option value="number">Number</option>
                    <option value="textarea">Textarea</option>
                    <option value="select">Select</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Options (pisah koma, khusus Select)</label>
                <input type="text" name="custom_fields[${customFieldIndex}][options]"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex items-end gap-2">
                <label class="flex items-center gap-1.5 text-xs text-gray-600">
                    <input type="checkbox" name="custom_fields[${customFieldIndex}][is_required]" value="1"
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    Wajib
                </label>
                <button type="button" onclick="this.closest('.custom-field-item').remove()"
                    class="text-xs text-red-400 hover:text-red-600 ml-auto">Hapus</button>
            </div>
        </div>`;
    container.appendChild(div);
    customFieldIndex++;
}

function addPhase() {
    const container = document.getElementById('phasesContainer');
    const div = document.createElement('div');
    div.className = 'border border-gray-100 rounded-xl p-4 phase-item';
    div.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs text-gray-500">Phase ${phaseIndex + 1}</span>
            <button type="button" onclick="this.closest('.phase-item').remove()"
                class="text-xs text-red-400 hover:text-red-600">Hapus</button>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Nama Phase *</label>
                <input type="text" name="phases[${phaseIndex}][name]" placeholder="General Sale"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                <input type="datetime-local" name="phases[${phaseIndex}][start_time]"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">End Time</label>
                <input type="datetime-local" name="phases[${phaseIndex}][end_time]"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>`;
    container.appendChild(div);
    phaseIndex++;
}

function addCategory() {
    const container = document.getElementById('categoriesContainer');
    const div = document.createElement('div');
    div.className = 'border border-gray-100 rounded-xl p-4';
    div.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs text-gray-500">Kategori ${categoryIndex + 1}</span>
            <button type="button" onclick="this.closest('div.border').remove()"
                class="text-xs text-red-400 hover:text-red-600">Hapus</button>
        </div>
        <div class="grid grid-cols-4 gap-3">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Nama *</label>
                <input type="text" name="categories[${categoryIndex}][name]" placeholder="CAT 2"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Fee (Rp) *</label>
                <input type="number" name="categories[${categoryIndex}][fee_per_ticket]"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Payment Mode</label>
                <select name="categories[${categoryIndex}][payment_mode]"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="service_fee_only">Fee Only</option>
                    <option value="full_payment">Full Payment</option>
                    <option value="custom_payment">Custom</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Max Qty</label>
                <input type="number" name="categories[${categoryIndex}][max_qty]" value="4" min="1"
                    class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>`;
    container.appendChild(div);
    categoryIndex++;
}
</script>
@endsection