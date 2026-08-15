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
     * Nomor urut diambil dari token TERTINGGI yang pernah dipakai untuk
     * event ini (bukan dari jumlah baris order), supaya gak kebentur
     * token yang udah pernah dipakai kalau ada order yang gagal/kehapus
     * di tengah jalan. Tetap dibungkus lock + retry loop buat jaga-jaga
     * race condition (2 request bersamaan).
     */
    public function generate(Event $event): string
    {
        $prefix = $this->resolvePrefix($event);

        return DB::transaction(function () use ($event, $prefix) {
            // Lock baris event ini supaya request lain nunggu giliran
            // sampai transaksi ini selesai (mencegah baca angka yang sama).
            Event::where('id', $event->id)->lockForUpdate()->first();

            $lastNumber = Order::where('event_id', $event->id)
                ->where('telegram_link_token', 'like', "{$prefix}-wrtx%")
                ->pluck('telegram_link_token')
                ->map(fn ($t) => (int) str_replace("{$prefix}-wrtx", '', $t))
                ->max() ?? 0;

            $attempt = 0;
            do {
                $token = $this->buildToken($prefix, $lastNumber + 1 + $attempt);
                $attempt++;
            } while (Order::where('telegram_link_token', $token)->exists() && $attempt < 30);

            return $token;
        });
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