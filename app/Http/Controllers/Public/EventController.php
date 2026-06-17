<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['salePhases', 'ticketCategories'])
            ->whereIn('status', ['upcoming', 'ongoing']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->whereFullText(['title', 'artist_name', 'description', 'venue', 'city', 'event_type'], $search)
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('artist_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        if ($city = $request->get('city')) {
            $query->where('city', $city);
        }

        if ($type = $request->get('type')) {
            $query->where('event_type', $type);
        }

        if ($platform = $request->get('platform')) {
            $query->where('platform_type', $platform);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($month = $request->get('month')) {
            $query->whereMonth('event_date', $month);
        }

        $events = $query->latest('event_date')->paginate(12)->withQueryString();
        $events->getCollection()->transform(function (Event $event) {
            $totalAccounts = Order::where('event_id', $event->id)
                ->distinct('email')
                ->count('email');

            $successAccounts = Order::where('event_id', $event->id)
                ->where('order_status', 'success')
                ->distinct('email')
                ->count('email');

            $event->setAttribute('total_accounts', $totalAccounts);
            $event->setAttribute('success_accounts', $successAccounts);
            $event->setAttribute('success_rate', $totalAccounts > 0
                ? round(($successAccounts / $totalAccounts) * 100, 1)
                : 0.0);

            return $event;
        });

        $cities    = Event::whereIn('status', ['upcoming', 'ongoing'])->distinct()->pluck('city');
        $types     = Event::whereIn('status', ['upcoming', 'ongoing'])->distinct()->pluck('event_type');

        return view('public.events.index', compact('events', 'cities', 'types'));
    }

    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)
            ->with(['salePhases', 'ticketCategories' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }, 'customFields' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->firstOrFail();

        return view('public.events.show', compact('event'));
    }
}
