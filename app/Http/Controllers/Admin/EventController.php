<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('orders')
            ->latest()
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load([
            'salePhases',
            'ticketCategories',
            'orders.ticketCategory',
            'successLogs.ticketCategory',
        ]);

        $stats = [
            'total_orders'   => $event->orders->count(),
            'success_orders' => $event->orders->where('order_status', 'success')->count(),
            'pending_orders' => $event->orders->where('order_status', 'waiting')->count(),
            'total_revenue'  => $event->orders->where('payment_status', 'paid')->sum('grand_total'),
        ];

        return view('admin.events.show', compact('event', 'stats'));
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event berhasil dihapus.');
    }

    public function updateStatus(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|in:upcoming,ongoing,finished',
        ]);

        $event->update(['status' => $request->status]);

        if ($request->status === 'finished') {
            dispatch(new \App\Jobs\TriggerN8nWebhook([
                'event_type' => 'event_finished',
                'event_id'   => $event->id,
                'event_title'=> $event->title,
            ]));
        }

        return back()->with('success', 'Status event berhasil diupdate.');
    }
}