<?php
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\OrderController;
use App\Http\Controllers\Public\RealtimeMonitorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::post('/orders', [OrderController::class, 'store'])
    ->name('orders.store')
    ->middleware('throttle:10,1');
Route::get('/order-success/{orderCode}', [OrderController::class, 'success'])->name('order.success');
Route::get('/monitor', [RealtimeMonitorController::class, 'index'])->name('monitor');