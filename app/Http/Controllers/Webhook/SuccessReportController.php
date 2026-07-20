<?php
namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SuccessLog;
use App\Models\Setting;
use App\Models\TicketCategory;
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
            'email'        => 'required|email',
            'status'       => 'required|string',
            'keyword'      => 'nullable|string',
            'package_name' => 'nullable|string',
            'raw_report'   => 'nullable|string',
        ]);

        $email       = $request->input('email');
        $keyword     = strtolower(trim($request->input('keyword', '')));
        $packageName = trim($request->input('package_name', ''));
        $rawReport   = $request->input('raw_report', '');

        $order = Order::where('email', $email)
            ->where('order_status', 'waiting')
            ->with(['event', 'salePhase', 'categoryChoices.ticketCategory'])
            ->latest()
            ->first();

        if (!$order) {
            Log::warning("SuccessReport: no waiting order for email {$email}");
            return response()->json([
                'found'   => false,
                'message' => "Tidak ada order aktif untuk email {$email}",
            ], 404);
        }

        // Match kategori yang berhasil berdasarkan keyword/nama paket dari pesan bot
        $matchedCategory = $this->matchCategory($rawReport, $order, $keyword, $packageName);

        // Hitung ulang fee sesuai kategori yang match
        $qty              = $order->qty;
        $serviceFeeTotal  = $matchedCategory->fee_per_ticket * $qty;
        $ticketPriceTotal = 0;
        $grandTotal       = 0;

        if ($matchedCategory->payment_mode === 'service_fee_only') {
            $grandTotal = $serviceFeeTotal;
        } elseif ($matchedCategory->payment_mode === 'full_payment') {
            $ticketPriceTotal = $matchedCategory->ticket_price * $qty;
            $grandTotal       = $serviceFeeTotal + $ticketPriceTotal;
        } elseif ($matchedCategory->payment_mode === 'custom_payment') {
            $grandTotal = $matchedCategory->custom_payment_amount ?? $serviceFeeTotal;
        }

        // Update order: status + kategori yang match + fee final
        $order->update([
            'order_status'       => 'success',
            'ticket_category_id' => $matchedCategory->id,
            'service_fee_total'  => $serviceFeeTotal,
            'ticket_price_total' => $ticketPriceTotal,
            'grand_total'        => $grandTotal,
            'payment_mode'       => $matchedCategory->payment_mode,
        ]);

        // Success log
        $log = SuccessLog::create([
            'order_id'           => $order->id,
            'event_id'           => $order->event_id,
            'sale_phase_id'      => $order->sale_phase_id,
            'ticket_category_id' => $matchedCategory->id,
            'email'              => $order->email,
            'username'           => $order->telegram_username,
            'qty'                => $order->qty,
            'status'             => 'success',
            'raw_report'         => $rawReport,
        ]);

        broadcast(new SuccessLogCreated($log, $order))->toOthers();

        $chatId = $order->telegram_chat_id;
        if ($chatId) {
            dispatch(new SendTelegramNotification([
                'type'     => 'success_notif',
                'order_id' => $order->id,
                'chat_id'  => $chatId,
            ]));
        }

        // Generate QRIS sesuai fee kategori yang match
        dispatch(new CreateDompetxPayment($order->id));

        \App\Models\AdminNotification::notifySuccessReport($order);

        Log::info("SuccessReport: order {$order->order_code} matched to '{$matchedCategory->name}', fee Rp{$grandTotal}");

        return response()->json([
            'found'         => true,
            'order_code'    => $order->order_code,
            'category_name' => $matchedCategory->name,
            'grand_total'   => $grandTotal,
            'chat_id'       => $chatId,
        ]);
    }

    private function matchCategory(string $rawReport, Order $order, string $keyword = '', string $packageName = ''): TicketCategory
    {
        $choices = $order->categoryChoices->sortBy('priority');

        // 1. Match dari keyword persis (Tiket.com punya field "(keyword: xxx)")
        if ($keyword) {
            foreach ($choices as $choice) {
                $cat = $choice->ticketCategory;
                if (!$cat->keyword) continue;

                $catKeyword = strtolower(trim($cat->keyword));

                // Exact match
                if ($catKeyword === $keyword) {
                    Log::info("Category matched by keyword (exact): {$cat->keyword}");
                    return $cat;
                }

                // Normalized match (hapus spasi: "cat 1" == "cat1")
                $keywordNorm    = preg_replace('/\s+/', '', $keyword);
                $catKeywordNorm = preg_replace('/\s+/', '', $catKeyword);
                if ($keywordNorm === $catKeywordNorm) {
                    Log::info("Category matched by keyword (normalized): {$cat->keyword}");
                    return $cat;
                }
            }
        }

        // 2. Match dari package_name vs keyword kategori
        if ($packageName) {
            foreach ($choices as $choice) {
                $cat = $choice->ticketCategory;
                if ($cat->keyword && stripos($packageName, $cat->keyword) !== false) {
                    Log::info("Category matched by keyword in package_name: {$cat->keyword}");
                    return $cat;
                }
            }
        }

        // 3. Match dari package_name vs nama kategori (Yesplis, Loket)
        if ($packageName) {
            foreach ($choices as $choice) {
                $cat = $choice->ticketCategory;
                if (stripos($packageName, $cat->name) !== false ||
                    stripos($cat->name, $packageName) !== false) {
                    Log::info("Category matched by name: {$cat->name}");
                    return $cat;
                }
            }
        }

        // 4. Safety net: cari nama kategori di raw_report
        foreach ($choices as $choice) {
            $cat = $choice->ticketCategory;
            if (stripos($rawReport, $cat->name) !== false) {
                Log::info("Category matched by name in raw_report: {$cat->name}");
                return $cat;
            }
        }

        // 5. Fallback: kategori utama (priority 1)
        $primaryChoice = $choices->first();
        Log::info("Category fallback to primary: {$primaryChoice->ticketCategory->name}");
        return $primaryChoice->ticketCategory;
    }
}