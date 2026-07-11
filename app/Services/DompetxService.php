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
    private string $apiKey;
    private string $baseUrl = 'https://api.dompetx.com/v1';

    public function __construct()
    {
        $this->apiKey = Setting::get('dompetx_api_key', '');
    }

    private function generateSignature(string $timestamp, string $body): string
    {
        $signatureData = $timestamp . '.' . $body;
        return hash_hmac('sha256', $signatureData, $this->apiKey);
    }

    private function getHeaders(string $body, string $idempotencyKey): array
    {
        $timestamp = (string) time();
        $signature = $this->generateSignature($timestamp, $body);

        return [
            'Content-Type'       => 'application/json',
            'X-DOMPAY-API-Key'   => $this->apiKey,
            'X-DOMPAY-Signature' => $signature,
            'X-DOMPAY-Timestamp' => $timestamp,
            // Deterministik per order, bukan random tiap call,
            // supaya retry tidak membuat charge dobel di sisi gateway.
            'Idempotency-Key'    => $idempotencyKey,
        ];
    }

    public function createPayment(Order $order): ?array
    {
        if (!$this->apiKey) {
            Log::warning('DompetX API key not configured');
            return null;
        }

        $reference = 'WRTX-' . strtoupper(Str::random(12));

        $payload = [
            'method'          => 'QRIS',
            'amount'          => $order->grand_total,
            'currency'        => 'IDR',
            'reference'       => $reference,
            'settlementSpeed' => 'standard',
            'metadata'        => [
                'order_code' => $order->order_code,
                'event'      => $order->event->title ?? '',
                'customer'   => $order->full_name,
                'email'      => $order->email,
            ],
        ];

        $body = json_encode($payload);
        $idempotencyKey = 'order-' . $order->id . '-payment';

        try {
            $response = Http::withHeaders($this->getHeaders($body, $idempotencyKey))
                ->withBody($body, 'application/json')
                ->post("{$this->baseUrl}/payments");

            Log::info('DompetX create payment response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                PaymentLog::create([
                    'order_id'          => $order->id,
                    'provider'          => 'dompetx',
                    'payment_reference' => $reference,
                    'qris_url'          => $data['qris_url'] ?? null,
                    'amount'            => $order->grand_total,
                    'status'            => 'pending',
                    'expired_at'        => null,
                ]);

                $order->update(['payment_status' => 'pending']);

                Log::info("DompetX payment created: {$reference}");
                return $data;
            }

            Log::error('DompetX create payment failed: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('DompetX exception: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyCallback(string $rawBody, string $signature, string $timestamp): bool
    {
        if (!$this->apiKey) return false;

        $now = time();
        if (abs($now - (int) $timestamp) > 300) {
            Log::warning('DompetX callback: timestamp expired');
            return false;
        }

        $expected = $this->generateSignature($timestamp, $rawBody);
        return hash_equals($expected, $signature);
    }

    public function handleCallback(array $data, string $rawBody, string $signature, string $timestamp): bool
    {
        if (!$this->verifyCallback($rawBody, $signature, $timestamp)) {
            Log::warning('DompetX callback: invalid signature');
            return false;
        }

        $reference = $data['data']['reference'] ?? null;
        $status    = $data['data']['status'] ?? null;

        if (!$reference || !$status) {
            Log::warning('DompetX callback: missing data', $data);
            return false;
        }

        $paymentLog = PaymentLog::where('payment_reference', $reference)->first();

        if (!$paymentLog) {
            Log::warning("DompetX callback: payment log not found for {$reference}");
            return false;
        }

        if ($paymentLog->status === 'paid') {
            Log::info("DompetX callback: already processed {$reference}");
            return true;
        }

        $newStatus = match($status) {
            'paid'    => 'paid',
            'expired' => 'expired',
            'failed'  => 'failed',
            default   => 'pending',
        };

        $paymentLog->update([
            'status'           => $newStatus,
            'paid_at'          => $status === 'paid' ? now() : null,
            'callback_payload' => $data,
        ]);

        $order = $paymentLog->order;
        if (!$order) return false;

        if ($status === 'paid') {
            $order->update(['payment_status' => 'paid']);
            $this->handlePaid($order);
        } elseif ($status === 'expired') {
            $order->update(['payment_status' => 'expired']);
            $this->handleExpired($order);
        }

        // Broadcast realtime ke admin Orders page
        broadcast(new \App\Events\PaymentStatusUpdated($order))->toOthers();

        Log::info("DompetX callback processed: {$reference} → {$newStatus}");
        return true;
    }

    private function handlePaid(Order $order): void
    {
        $chatId = $this->getChatId($order);

        if ($chatId) {
            dispatch(new \App\Jobs\SendTelegramNotification([
                'type'       => 'payment_paid',
                'order_id'   => $order->id,
                'order_code' => $order->order_code,
                'chat_id'    => $chatId,
            ]));
        }

        dispatch(new \App\Jobs\TriggerN8nWebhook([
            'event_type'  => 'payment_paid',
            'order_code'  => $order->order_code,
            'amount'      => $order->grand_total,
        ]));

        // Notify admin about fee payment success
        \App\Models\AdminNotification::notifyPaymentPaid($order);
    }

    private function handleExpired(Order $order): void
    {
        $chatId = $this->getChatId($order);

        if ($chatId) {
            dispatch(new \App\Jobs\SendTelegramNotification([
                'type'       => 'payment_expired',
                'order_id'   => $order->id,
                'order_code' => $order->order_code,
                'chat_id'    => $chatId,
            ]));
        }
    }

    private function getChatId(Order $order): ?string
    {
        return $order->telegram_chat_id
            ?? $order->telegramConnection?->telegram_chat_id
            ?? null;
    }
}