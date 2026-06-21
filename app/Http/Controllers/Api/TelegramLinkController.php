<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Scopes\HideUnlinkedOrdersScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramLinkController extends Controller
{
    public function verify(Request $request)
    {
        $secret   = Setting::get('n8n_webhook_secret', '');
        $incoming = $request->header('X-Wartix-Secret', '');

        if ($secret && !hash_equals($secret, $incoming)) {
            return response()->json(['valid' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'token'   => 'required|string',
            'chat_id' => 'required|string',
        ]);

        $token = $request->input('token');

        $order = Order::withoutGlobalScope(HideUnlinkedOrdersScope::class)
            ->where('telegram_link_token', $token)
            ->with(['event', 'salePhase', 'ticketCategory'])
            ->first();

        if (!$order) {
            return response()->json([
                'valid'   => false,
                'message' => 'Token tidak ditemukan. Pastikan kamu klik link dari halaman Wartix.',
            ]);
        }

        if ($order->order_status !== 'pending_link') {
            return response()->json([
                'valid'   => false,
                'message' => 'Token ini sudah pernah digunakan atau order sudah tidak aktif.',
            ]);
        }

        // Aktifkan order: pindah dari pending_link -> waiting, simpan chat_id
        $order->update([
            'telegram_chat_id'   => $request->input('chat_id'),
            'telegram_user_id'   => $request->input('telegram_user_id'),
            'telegram_username'  => $request->input('telegram_username') ?: $order->telegram_username,
            'telegram_linked_at' => now(),
            'order_status'       => 'waiting',
        ]);

        // Invalidate cache supaya stats admin & home langsung update
        Cache::forget('active_events');
        Cache::forget('home_stats');

        Log::info("Telegram link verified, order activated: {$order->order_code}");

        $adminUsername = Setting::get('telegram_admin_username', 'admin_wartix');

        return response()->json([
            'valid' => true,
            'order' => [
                'order_code'    => $order->order_code,
                'event_title'   => $order->event->title ?? '-',
                'phase_name'    => $order->salePhase->name ?? '-',
                'category_name' => $order->ticketCategory->name ?? '-',
                'qty'           => $order->qty,
                'grand_total'   => $order->grand_total,
                'full_name'     => $order->full_name,
            ],
            'admin_username' => $adminUsername,
        ]);
    }
}