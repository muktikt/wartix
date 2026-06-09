<?php
namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhitelistWebhookIp
{
    public function handle(Request $request, Closure $next, string $type = 'n8n')
    {
        $envKey    = $type === 'dompetx' ? 'DOMPETX_WHITELIST_IP' : 'N8N_WHITELIST_IP';
        $whitelist = env($envKey, '');

        // Kalau kosong, skip check (development mode)
        if (empty(trim($whitelist))) {
            return $next($request);
        }

        $allowedIps = array_map('trim', explode(',', $whitelist));
        $clientIp   = $request->ip();

        if (!in_array($clientIp, $allowedIps)) {
            Log::warning("Webhook IP blocked: {$clientIp} (type: {$type})");
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}