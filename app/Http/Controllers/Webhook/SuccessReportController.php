<?php
namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SuccessLog;
use App\Models\Setting;
use App\Models\Scopes\HideUnlinkedOrdersScope;
use App\Jobs\CreateDompetxPayment;
use App\Jobs\SendTelegramNotification;
use App\Events\SuccessLogCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuccessReportController extends Controller
{
    public function handle(Request $request)
    {
        $secret   = Setting::get('n8n_webhook_secret', '');
        $incoming = $request->header('X-Wartix-Secret', '');

        if ($secret && !hash_equals($secret, $incoming)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'email'  => 'required|email',
            'status' => 'required|string',
        ]);

        $email     = $request->input('email');
        $status    = $request->input('status', 'success');
        $rawReport = $request->input('raw_report', '');

        // Cari order aktif (waiting) dengan email ini
        // withoutGlobalScope tidak diperlukan karena order yang relevan
        // sudah pasti status "waiting" (sudah lolos pending_link)
        $order = Order::where('email', $email)
            ->where('order_status', 'waiting')
            ->with(['event', 'salePhase', 'ticketCategory'])
            ->latest()
            ->first();

        if (!$order) {
            Log::warning("SuccessReport: no waiting order found for email {$email}");
            return response()->json([
                'found'   => false,
                'message' => "Tidak ada order aktif untuk email {$email}",
            ], 404);
        }

        // Update order status
        $order->update(['order_status' => 'success']);

        // Simpan success log
        $log = SuccessLog::create([
            'order_id'           => $order->id,
            'event_id'           => $order->event_id,
            'sale_phase_id'      => $order->sale_phase_id,
            'ticket_category_id'=> $order->ticket_category_id,
            'email'              => $order->email,
            'username'           => $order->telegram_username,
            'qty'                => $order->qty,
            'status'             => 'success',
            'raw_report'         => $rawReport,
        ]);

        // Broadcast ke realtime monitor
        broadcast(new SuccessLogCreated($log, $order))->toOthers();

        // Kirim notif sukses ke CUSTOMER (chat_id sudah tersimpan dari link verification)
        $chatId = $order->telegram_chat_id;

        if ($chatId) {
            dispatch(new SendTelegramNotification([
                'type'     => 'success_notif',
                'order_id' => $order->id,
                'chat_id'  => $chatId,
            ]));
        }

        // Buat payment DompetX (QRIS sesuai kategori order)
        dispatch(new CreateDompetxPayment($order->id));

        // Notify admin about successful ticket booking
        \App\Models\AdminNotification::notifySuccessReport($order);

        Log::info("SuccessReport processed for email: {$email}, order: {$order->order_code}");

        return response()->json([
            'found'      => true,
            'order_code' => $order->order_code,
            'chat_id'    => $chatId,
        ]);
    }
}