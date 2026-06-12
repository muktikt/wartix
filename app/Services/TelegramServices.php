<?php
namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private string $token;
    private string $baseUrl;

    public function __construct()
    {
        $this->token   = Setting::get('telegram_bot_token', '');
        $this->baseUrl = "https://api.telegram.org/bot{$this->token}";
    }

    public function sendMessage(string $chatId, string $text, string $parseMode = 'HTML'): bool
    {
        if (!$this->token) {
            Log::warning('Telegram token not configured');
            return false;
        }

        try {
            $response = Http::post("{$this->baseUrl}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $text,
                'parse_mode' => $parseMode,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendPhoto(string $chatId, string $photoUrl, string $caption = ''): bool
    {
        if (!$this->token) return false;

        try {
            $response = Http::post("{$this->baseUrl}/sendPhoto", [
                'chat_id' => $chatId,
                'photo'   => $photoUrl,
                'caption' => $caption,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram sendPhoto failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendPaymentInfo(string $chatId, array $data): bool
    {
        $text = "💳 <b>PAYMENT INFORMATION — Wartix</b>\n\n";
        $text .= "Event: <b>{$data['event']}</b>\n";
        $text .= "Sale Phase: {$data['phase']}\n";
        $text .= "Kategori: {$data['category']}\n";
        $text .= "Jumlah Tiket: {$data['qty']}\n\n";
        $text .= "━━━━━━━━━━━━━━━\n";

        if (isset($data['ticket_price']) && $data['ticket_price'] > 0) {
            $text .= "Ticket Price: Rp " . number_format($data['ticket_price']) . "\n";
        }

        $text .= "Service Fee: Rp " . number_format($data['service_fee']) . "\n";
        $text .= "Admin Fee: Rp " . number_format($data['admin_fee'] ?? 0) . "\n";
        $text .= "Grand Total: <b>Rp " . number_format($data['grand_total']) . "</b>\n";
        $text .= "━━━━━━━━━━━━━━━\n\n";
        $text .= "⏳ Payment Expired: {$data['expired_minutes']} Menit\n\n";
        $text .= "Silakan scan QRIS berikut untuk melanjutkan proses ticket release.";

        return $this->sendMessage($chatId, $text);
    }

    public function sendSuccessNotif(string $chatId, array $data): bool
    {
        $text = "✅ <b>Tiket Berhasil!</b>\n\n";
        $text .= "Event: <b>{$data['event']}</b>\n";
        $text .= "Phase: {$data['phase']}\n";
        $text .= "Kategori: {$data['category']}\n";
        $text .= "Qty: {$data['qty']} tiket\n\n";
        $text .= "Silakan bayar fee jasa berikut untuk konfirmasi.";

        return $this->sendMessage($chatId, $text);
    }

    public function sendPaymentPaidNotif(string $chatId, string $orderCode): bool
    {
        $text = "✅ <b>Pembayaran Diterima — Wartix</b>\n\n";
        $text .= "Order <code>{$orderCode}</code> telah lunas.\n";
        $text .= "Terima kasih telah menggunakan layanan Wartix! 🎫";

        return $this->sendMessage($chatId, $text);
    }

    public function sendPaymentExpiredNotif(string $chatId, string $orderCode): bool
    {
        $text = "❌ <b>Pembayaran Expired — Wartix</b>\n\n";
        $text .= "QRIS untuk order <code>{$orderCode}</code> telah expired.\n";
        $text .= "Hubungi admin Wartix untuk informasi lebih lanjut.";

        return $this->sendMessage($chatId, $text);
    }

    public function sendRekapWithPhoto(
        string $chatId,
        string $imagePath,
        string $caption
    ): bool {
        if (!$this->token) return false;

        try {
            $response = Http::attach(
                'photo',
                file_get_contents($imagePath),
                'rekap_' . time() . '.jpg'
            )->post("{$this->baseUrl}/sendPhoto", [
                'chat_id'    => $chatId,
                'caption'    => $caption,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram sendRekapWithPhoto failed: ' . $e->getMessage());
            return false;
        }
    }
}
