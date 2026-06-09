<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',

        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            // Webhook — pakai web middleware tapi exclude CSRF
            Route::middleware('web')
                ->group(base_path('routes/webhook.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.admin' => \App\Http\Middleware\AdminAuth::class,
            'webhook.whitelist'=> \App\Http\Middleware\WhitelistWebhookIp::class,
        ]);

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Exclude webhook dari CSRF
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();