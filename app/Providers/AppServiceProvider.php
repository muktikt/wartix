<?php
namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('order-by-email', function (Request $request) {
            return Limit::perHour(3)
                ->by($request->input('email', $request->ip()))
                ->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak order. Coba lagi dalam 1 jam.'
                    ], 429);
                });
        });
    }
}