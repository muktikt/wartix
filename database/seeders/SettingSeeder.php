<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'telegram_group_link',      'value' => '',    'type' => 'string'],
            ['key' => 'telegram_bot_token',        'value' => '',    'type' => 'string'],
            ['key' => 'n8n_webhook_url',           'value' => '',    'type' => 'string'],
            ['key' => 'n8n_webhook_secret',        'value' => '',    'type' => 'string'],
            ['key' => 'dompetx_merchant_id',       'value' => '',    'type' => 'string'],
            ['key' => 'dompetx_api_key',           'value' => '',    'type' => 'string'],
            ['key' => 'dompetx_callback_url',      'value' => '',    'type' => 'string'],
            ['key' => 'threads_access_token',      'value' => '',    'type' => 'string'],
            ['key' => 'threads_user_id',           'value' => '',    'type' => 'string'],
            ['key' => 'threads_auto_post',         'value' => '0',   'type' => 'boolean'],
            ['key' => 'payment_expired_minutes',   'value' => '10',  'type' => 'integer'],
            ['key' => 'n8n_whitelist_ip',          'value' => '',    'type' => 'string'],
            ['key' => 'dompetx_whitelist_ip',      'value' => '',    'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}