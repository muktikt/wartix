<?php
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\OrderController;
use App\Http\Controllers\Public\RealtimeMonitorController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::post('/orders', [OrderController::class, 'store'])
    ->name('orders.store')
    ->middleware(['throttle:10,1', 'throttle:order-by-email']);
Route::get('/order-success/{orderCode}', [OrderController::class, 'success'])->name('order.success');
Route::get('/monitor', [RealtimeMonitorController::class, 'index'])->name('monitor');

Route::get('/sitemap.xml', function () {
    $baseUrl = config('app.url', 'https://warindong.com');
    $events = Event::select('slug', 'updated_at')->latest()->get();

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    $xml .= '<url><loc>' . rtrim($baseUrl, '/') . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>';
    $xml .= '<url><loc>' . rtrim($baseUrl, '/') . '/events</loc><changefreq>daily</changefreq><priority>0.9</priority></url>';
    $xml .= '<url><loc>' . rtrim($baseUrl, '/') . '/monitor</loc><changefreq>hourly</changefreq><priority>0.8</priority></url>';

    foreach ($events as $event) {
        $xml .= '<url>';
        $xml .= '<loc>' . rtrim($baseUrl, '/') . '/events/' . $event->slug . '</loc>';
        $xml .= '<lastmod>' . ($event->updated_at ? $event->updated_at->toAtomString() : date('c')) . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
    }

    $xml .= '</urlset>';

    return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
});