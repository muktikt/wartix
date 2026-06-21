<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class IntegrationSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.integrations.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'telegram_group_link'    => 'nullable|url',
            'telegram_bot_token'     => 'nullable|string',
            'telegram_bot_username'  => 'nullable|string|max:100',
            'n8n_webhook_url'        => 'nullable|url',
            'n8n_webhook_secret'     => 'nullable|string',
            'dompetx_merchant_id'    => 'nullable|string',
            'dompetx_api_key'        => 'nullable|string',
            'threads_access_token'   => 'nullable|string',
            'threads_user_id'        => 'nullable|string',
            'payment_expired_minutes'=> 'nullable|integer|min:5|max:60',
            'default_slot_availability' => 'nullable|integer|min:0',
            'n8n_whitelist_ip'       => 'nullable|string',
            'dompetx_whitelist_ip'   => 'nullable|string',
        ]);

        $keys = [
            'telegram_group_link',
            'telegram_bot_token',
            'telegram_bot_username',
            'telegram_group_chat_id',
            'n8n_webhook_url',
            'n8n_webhook_secret',
            'dompetx_merchant_id',
            'dompetx_api_key',
            'dompetx_callback_url',
            'threads_access_token',
            'threads_user_id',
            'payment_expired_minutes',
            'default_slot_availability',
            'n8n_whitelist_ip',
            'dompetx_whitelist_ip',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->get($key));
            }
        }

        Setting::set('threads_auto_post', $request->boolean('threads_auto_post') ? '1' : '0');

        return back()->with('success', 'Integration settings berhasil disimpan.');
    }
}