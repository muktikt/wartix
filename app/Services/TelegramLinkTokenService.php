<?php
namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Support\Str;

class TelegramLinkTokenService
{
    /**
     * Generate token unik per event, format: {prefix}-wrtx{nomor urut}
     * Contoh: bts-wrtx01, bts-wrtx02, dst.
     */
    public function generate(Event $event): string
    {
        $prefix = $this->resolvePrefix($event);

        // Hitung order ke berapa untuk event ini (termasuk yang baru akan dibuat)
        $existingCount = Order::where('event_id', $event->id)->count();
        $nextNumber    = $existingCount + 1;

        $token = $this->buildToken($prefix, $nextNumber);

        // Jaga-jaga kalau ada race condition / token bentrok, increment sampai unik
        while (Order::where('telegram_link_token', $token)->exists()) {
            $nextNumber++;
            $token = $this->buildToken($prefix, $nextNumber);
        }

        return $token;
    }

    private function buildToken(string $prefix, int $number): string
    {
        $padded = str_pad((string) $number, 2, '0', STR_PAD_LEFT);
        return "{$prefix}-wrtx{$padded}";
    }

    /**
     * Ambil kata pertama dari judul event, lowercase, alfanumerik saja.
     * "BTS World Tour" -> "bts"
     * "2026 Summer Fest" -> "2026"
     */
    private function resolvePrefix(Event $event): string
    {
        $firstWord = Str::of($event->title)->trim()->explode(' ')->first() ?? 'wartix';
        $clean     = Str::of($firstWord)->lower()->replaceMatches('/[^a-z0-9]/', '');

        return $clean->isEmpty() ? 'wartix' : (string) $clean;
    }
}