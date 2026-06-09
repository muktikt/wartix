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
        $rawBody   = $request->getContent();
        $signature = $request->header('X-DOMPAY-Signature', '');
        $timestamp = $request->header('X-DOMPAY-Timestamp', '');

        Log::info('DompetX callback received', [
            'signature' => $signature,
            'timestamp' => $timestamp,
            'body'      => $rawBody,
        ]);

        $data   = $request->all();
        $result = $dompetx->handleCallback($data, $rawBody, $signature, $timestamp);

        if (!$result) {
            return response()->json(['error' => 'Failed to process callback'], 422);
        }

        return response()->json(['status' => 'ok']);
    }
}