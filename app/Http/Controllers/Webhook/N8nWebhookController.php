<?php
namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class N8nWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Validate secret
        $secret   = Setting::get('n8n_webhook_secret', '');
        $incoming = $request->header('X-Wartix-Secret', '');

        if ($secret && !hash_equals($secret, $incoming)) {
            Log::warning('n8n webhook: invalid secret');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $eventType = $request->input('event_type');
        Log::info("n8n webhook received: {$eventType}");

        return response()->json(['status' => 'ok']);
    }
}