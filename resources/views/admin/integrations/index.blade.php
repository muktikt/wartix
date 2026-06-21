@extends('layouts.admin')
@section('title', 'Integration Settings')
@section('page-title', 'Integration Settings')

@section('content')
<form action="{{ route('admin.integrations.update') }}" method="POST">
    @csrf
    <div class="grid grid-cols-2 gap-5">

        {{-- Telegram --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.412 14.02l-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.834.566z"/>
                </svg>
                Telegram
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Group Link</label>
                    <input type="url" name="telegram_group_link" value="{{ $settings['telegram_group_link'] ?? '' }}"
                        placeholder="https://t.me/..."
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Bot Token</label>
                    <input type="password" name="telegram_bot_token" value="{{ $settings['telegram_bot_token'] ?? '' }}"
                        placeholder="1234567890:ABCdef..."
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        Bot Username
                        <span class="text-gray-400 font-normal">t.me/WartixcomBot</span>
                    </label>
                    <input type="text" name="telegram_bot_username"
                        value="{{ $settings['telegram_bot_username'] ?? '' }}"
                        placeholder="WartixBot"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                {{-- Di bagian Telegram --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        Group Chat ID
                        <span class="text-gray-400 font-normal">(untuk kirim rekap foto)</span>
                    </label>
                    <input type="text" name="telegram_group_chat_id"
                        value="{{ $settings['telegram_group_chat_id'] ?? '' }}"
                        placeholder="-100xxxxxxxxxx"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-400 mt-1">
                        Cara dapat Chat ID: forward pesan dari grup ke @userinfobot
                    </p>
                </div>
            </div>
        </div>

        {{-- n8n --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">n8n Webhook</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Webhook URL</label>
                    <input type="url" name="n8n_webhook_url" value="{{ $settings['n8n_webhook_url'] ?? '' }}"
                        placeholder="https://your-n8n.sumopod.app/webhook/..."
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Webhook Secret</label>
                    <input type="password" name="n8n_webhook_secret" value="{{ $settings['n8n_webhook_secret'] ?? '' }}"
                        placeholder="secret key"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                {{-- di card n8n, setelah Webhook Secret --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Whitelist IP (pisah koma, kosongkan = skip cek)</label>
                        <input type="text" name="n8n_whitelist_ip" value="{{ $settings['n8n_whitelist_ip'] ?? '' }}"
                            placeholder="1.2.3.4, 5.6.7.8"
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- DompetX --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">DompetX Payment</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Merchant ID</label>
                    <input type="text" name="dompetx_merchant_id" value="{{ $settings['dompetx_merchant_id'] ?? '' }}"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">API Key</label>
                    <input type="password" name="dompetx_api_key" value="{{ $settings['dompetx_api_key'] ?? '' }}"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        Callback URL
                        <span class="text-gray-400 font-normal">(daftarkan ke DompetX dashboard)</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text"
                            value="{{ url('/webhooks/dompetx/callback') }}"
                            readonly
                            class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 text-gray-500 cursor-not-allowed">
                        <button type="button"
                            onclick="navigator.clipboard.writeText('{{ url('/webhooks/dompetx/callback') }}')"
                            class="text-xs border border-gray-200 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600">
                            Copy
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Daftarkan URL ini di dashboard DompetX → Settings → Webhook
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Whitelist IP (pisah koma, kosongkan = skip cek)</label>
                    <input type="text" name="dompetx_whitelist_ip" value="{{ $settings['dompetx_whitelist_ip'] ?? '' }}"
                        placeholder="1.2.3.4, 5.6.7.8"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        {{-- Threads --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Threads Auto Post</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Access Token</label>
                    <input type="password" name="threads_access_token" value="{{ $settings['threads_access_token'] ?? '' }}"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">User ID</label>
                    <input type="text" name="threads_user_id" value="{{ $settings['threads_user_id'] ?? '' }}"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="threads_auto_post" value="1"
                        {{ ($settings['threads_auto_post'] ?? '0') === '1' ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    <span class="text-xs text-gray-700">Auto post ke Threads saat event dipublish</span>
                </label>
            </div>
        </div>
        
        {{-- Events --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Event Defaults</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Default Slot Availability</label>
                    <input type="number" name="default_slot_availability" value="{{ $settings['default_slot_availability'] ?? '' }}"
                        placeholder="Kosongkan untuk tak terbatas"
                        min="0"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-400 mt-1">Jika diisi, akan menjadi default slot untuk event baru (kosong = ∞).</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 flex justify-end">
        <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition-colors">
            Simpan Settings
        </button>
    </div>
</form>
@endsection