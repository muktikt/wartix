<?php
namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SuccessLog;
use App\Models\Setting;
use App\Jobs\CreateDompetxPayment;
use App\Jobs\SendTelegramNotification;
use App\Events\SuccessLogCreated;
use App\Services\MaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuccessReportController extends Controller
{
    public function handle(Request $request)
    {
        // Validate secret
        $secret   = Setting::get('n8n_webhook_secret', '');
        $incoming = $request->header('X-Wartix-Secret', '');

        if ($secret && !hash_equals($secret, $incoming)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'order_code' => 'required|string',
            'status'     => 'required|string',
        ]);

        $orderCode = $request->input('order_code');
        $status    = $request->input('status', 'success');
        $rawReport = $request->input('raw_report', '');

        $order = Order::where('order_code', $orderCode)
            ->with(['event', 'salePhase', 'ticketCategory'])
            ->first();

        if (!$order) {
            Log::warning("SuccessReport: order {$orderCode} not found");
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Update order status
        $order->update([
            'order_status' => 'success',
        ]);

        // Simpan success log
        $log = SuccessLog::create([
            'order_id'           => $order->id,
            'event_id'           => $order->event_id,
            'sale_phase_id'      => $order->sale_phase_id,
            'ticket_category_id' => $order->ticket_category_id,
            'email'              => $order->email,
            'username'           => $order->telegram_username,
            'qty'                => $order->qty,
            'status'             => 'success',
            'raw_report'         => $rawReport,
        ]);

        // Broadcast ke realtime monitor
        broadcast(new SuccessLogCreated($log, $order))->toOthers();

        // Kirim notif sukses ke user
        $chatId = $order->telegram_chat_id
            ?? $order->telegramConnection?->telegram_chat_id;

        if ($chatId) {
            dispatch(new SendTelegramNotification([
                'type'     => 'success_notif',
                'order_id' => $order->id,
                'chat_id'  => $chatId,
            ]));
        }

        // Buat payment DompetX
        dispatch(new CreateDompetxPayment($order->id));

        Log::info("SuccessReport processed: {$orderCode}");

        return response()->json([
            'status'  => 'ok',
            'message' => 'Success report processed',
        ]);
    }
}