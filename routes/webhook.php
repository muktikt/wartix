<?php
use App\Http\Controllers\Webhook\N8nWebhookController;
use App\Http\Controllers\Webhook\DompetxCallbackController;
use App\Http\Controllers\Webhook\SuccessReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->middleware('throttle:200,1')->group(function () {
    Route::post('n8n',              [N8nWebhookController::class, 'handle']);
    Route::post('dompetx/callback', [DompetxCallbackController::class, 'handle']);
    Route::post('success-report',   [SuccessReportController::class, 'handle']);
});