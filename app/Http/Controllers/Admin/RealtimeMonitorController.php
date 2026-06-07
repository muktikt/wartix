<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuccessLog;
use App\Models\Event;
use Illuminate\Http\Request;

class RealtimeMonitorController extends Controller
{
    public function index(Request $request)
    {
        $query = SuccessLog::with(['event', 'salePhase', 'ticketCategory', 'order'])
            ->latest();

        if ($eventId = $request->get('event_id')) {
            $query->where('event_id', $eventId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $logs   = $query->paginate(30)->withQueryString();
        $events = Event::orderBy('title')->get();

        return view('admin.monitor.index', compact('logs', 'events'));
    }
}