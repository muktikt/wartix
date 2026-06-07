<?php
namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\DompetxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DompetxCallbackController extends Controller
{
    public function handle(Request $request, DompetxService $dompetx)
    {
        // Verify HMAC signature
        $signature = $request->header('X-DompetX-Signature', '');
        $payload   = $request->getContent();

        if (!$dompetx->verifySignature($payload, $signature)) {
            Log::warning('DompetX callback: invalid signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data   = $request->all();
        $result = $dompetx->handleCallback($data);

        if (!$result) {
            Log::warning('DompetX callback: failed to process', $data);
            return response()->json(['error' => 'Failed to process'], 422);
        }

        return response()->json(['status' => 'ok']);
    }
}