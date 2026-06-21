<?php
use App\Http\Controllers\Webhook\N8nWebhookController;
use App\Http\Controllers\Webhook\DompetxCallbackController;
use App\Http\Controllers\Webhook\SuccessReportController;
use App\Http\Controllers\Api\TelegramLinkController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->group(function () {

    Route::middleware(['throttle:200,1', 'webhook.whitelist:n8n'])
        ->post('n8n', [N8nWebhookController::class, 'handle']);

    Route::middleware(['throttle:200,1', 'webhook.whitelist:dompetx'])
        ->post('dompetx/callback', [DompetxCallbackController::class, 'handle']);

    Route::middleware(['throttle:200,1', 'webhook.whitelist:n8n'])
        ->post('success-report', [SuccessReportController::class, 'handle']);

    Route::middleware(['throttle:200,1', 'webhook.whitelist:n8n'])
        ->post('telegram/verify-token', [TelegramLinkController::class, 'verify']);
});