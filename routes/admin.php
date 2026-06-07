<?php
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventBuilderController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RealtimeMonitorController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\IntegrationSettingController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    // Auth
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected
    Route::middleware('auth.admin')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Events
        Route::get('events', [EventController::class, 'index'])->name('events.index');
        Route::get('events/create/builder', [EventBuilderController::class, 'create'])->name('events.builder.create');
        Route::post('events/builder/store', [EventBuilderController::class, 'store'])->name('events.builder.store');
        Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
        Route::get('events/{event}/builder/edit', [EventBuilderController::class, 'edit'])->name('events.builder.edit');
        Route::put('events/{event}/builder/update', [EventBuilderController::class, 'update'])->name('events.builder.update');
        Route::patch('events/{event}/status', [EventController::class, 'updateStatus'])->name('events.status');
        Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        // Monitor
        Route::get('realtime-monitor', [RealtimeMonitorController::class, 'index'])->name('monitor.index');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        // Search
        Route::get('search', [SearchController::class, 'index'])->name('search.index');

        // Integration Settings
        Route::get('integration-settings', [IntegrationSettingController::class, 'index'])->name('integrations.index');
        Route::post('integration-settings', [IntegrationSettingController::class, 'update'])->name('integrations.update');

        // Export
        Route::get('export/orders', [ExportController::class, 'orders'])->name('export.orders');
        Route::get('export/guests', [ExportController::class, 'guests'])->name('export.guests');

        // Statistics
        Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    });
});