<?php
namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TelegramLinkTokenService
{
    /**
     * Generate token unik per event, format: {prefix}-wrtx{nomor urut}
     * Contoh: bts-wrtx01, bts-wrtx02, dst.
     *
     * Dibungkus retry loop supaya kalau ada race condition
     * (2 request bersamaan dapat angka urut sama), otomatis
     * coba lagi dengan angka berikutnya tanpa bikin order gagal.
     */
    public function generate(Event $event): string
    {
        $prefix  = $this->resolvePrefix($event);
        $maxTry  = 10;

        for ($attempt = 0; $attempt < $maxTry; $attempt++) {
            $token = DB::transaction(function () use ($event, $prefix) {
                // Lock baris event ini supaya request lain nunggu giliran
                // sampai transaksi ini selesai (mencegah baca angka yang sama).
                Event::where('id', $event->id)->lockForUpdate()->first();

                $existingCount = Order::where('event_id', $event->id)->count();
                $nextNumber    = $existingCount + 1 + $attempt;

                return $this->buildToken($prefix, $nextNumber);
            });

            if (!Order::where('telegram_link_token', $token)->exists()) {
                return $token;
            }
        }

        // Fallback super jarang terjadi: kalau 10x percobaan tetap bentrok,
        // tambahkan random suffix supaya pasti unik.
        return $this->buildToken($prefix, 0) . '-' . Str::lower(Str::random(4));
    }

    private function buildToken(string $prefix, int $number): string
    {
        $padded = str_pad((string) $number, 2, '0', STR_PAD_LEFT);
        return "{$prefix}-wrtx{$padded}";
    }

    private function resolvePrefix(Event $event): string
    {
        $firstWord = Str::of($event->title)->trim()->explode(' ')->first() ?? 'wartix';
        $clean     = Str::of($firstWord)->lower()->replaceMatches('/[^a-z0-9]/', '');

        return $clean->isEmpty() ? 'wartix' : (string) $clean;
    }
}