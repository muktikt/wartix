<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SuccessLog;
use App\Services\MaskService;

class RealtimeMonitorController extends Controller
{
    public function index()
    {
        $logs = SuccessLog::with(['event', 'salePhase', 'ticketCategory'])
            ->where('status', 'success')
            ->latest()
            ->limit(50)
            ->get()
            ->map(function ($log) {
                return [
                    'email'    => MaskService::email($log->email ?? 'us***@example.com'),
                    'event'    => $log->event->title ?? '-',
                    'phase'    => $log->salePhase->name ?? '-',
                    'category' => $log->ticketCategory->name ?? '-',
                    'qty'      => $log->qty,
                    'time'     => $log->created_at->diffForHumans(),
                ];
            });

        return view('public.monitor', compact('logs'));
    }
}