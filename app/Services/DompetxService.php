<?php
namespace App\Services;

use App\Models\Setting;
use App\Models\Order;
use App\Models\PaymentLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DompetxService
{
    private string $merchantId;
    private string $apiKey;
    private string $baseUrl = 'https://api.dompetx.id/v1';

    public function __construct()
    {
        $this->merchantId = Setting::get('dompetx_merchant_id', '');
        $this->apiKey     = Setting::get('dompetx_api_key', '');
    }

    public function createPayment(Order $order): ?array
    {
        if (!$this->merchantId || !$this->apiKey) {
            Log::warning('DompetX not configured');
            return null;
        }

        $reference = 'WRTX-' . strtoupper(Str::random(12));
        $expired   = (int) Setting::get('payment_expired_minutes', 10);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post("{$this->baseUrl}/qris/create", [
                'merchant_id'    => $this->merchantId,
                'reference_id'   => $reference,
                'amount'         => $order->grand_total,
                'expired_minute' => $expired,
                'description'    => "Fee Jasa Wartix - {$order->order_code}",
                'callback_url'   => url('/webhooks/dompetx/callback'),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                PaymentLog::create([
                    'order_id'          => $order->id,
                    'provider'          => 'dompetx',
                    'payment_reference' => $reference,
                    'qris_url'          => $data['data']['qris_url'] ?? null,
                    'amount'            => $order->grand_total,
                    'status'            => 'pending',
                    'expired_at'        => now()->addMinutes($expired),
                ]);

                $order->update(['payment_status' => 'pending']);

                return $data['data'] ?? null;
            }

            Log::error('DompetX create payment failed: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('DompetX exception: ' . $e->getMessage());
            return null;
        }
    }

    public function verifySignature(string $payload, string $signature): bool
    {
        $secret   = $this->apiKey;
        $expected = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expected, $signature);
    }

    public function handleCallback(array $data): bool
    {
        $reference = $data['reference_id'] ?? null;
        $status    = $data['status'] ?? null;

        if (!$reference || !$status) return false;

        // Idempotency check
        $paymentLog = PaymentLog::where('payment_reference', $reference)->first();
        if (!$paymentLog) return false;

        if ($paymentLog->status === 'paid') return true;

        $paymentLog->update([
            'status'           => $status === 'SUCCESS' ? 'paid' : ($status === 'EXPIRED' ? 'expired' : 'failed'),
            'paid_at'          => $status === 'SUCCESS' ? now() : null,
            'callback_payload' => $data,
        ]);

        $order = $paymentLog->order;

        if ($status === 'SUCCESS') {
            $order->update(['payment_status' => 'paid']);
            dispatch(new \App\Jobs\SendTelegramNotification([
                'type'       => 'payment_paid',
                'order_id'   => $order->id,
                'order_code' => $order->order_code,
                'chat_id'    => $order->telegram_chat_id,
            ]));
        } elseif ($status === 'EXPIRED') {
            $order->update(['payment_status' => 'expired']);
            dispatch(new \App\Jobs\SendTelegramNotification([
                'type'       => 'payment_expired',
                'order_id'   => $order->id,
                'order_code' => $order->order_code,
                'chat_id'    => $order->telegram_chat_id,
            ]));
        }

        return true;
    }
}