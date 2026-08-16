<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['salePhases', 'ticketCategories'])
            ->whereIn('status', ['upcoming', 'slot_penuh', 'ongoing', 'finished']);

        if ($search = trim($request->get('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('artist_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%");
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

        // Use subquery selects instead of N+1 per-event Order queries
        $query->addSelect([
            'events.*',
            \Illuminate\Support\Facades\DB::raw('(SELECT COUNT(DISTINCT email) FROM orders WHERE orders.event_id = events.id) as total_accounts_count'),
            \Illuminate\Support\Facades\DB::raw('(SELECT COUNT(DISTINCT email) FROM orders WHERE orders.event_id = events.id AND orders.order_status = \'success\') as success_accounts_count'),
        ]);

        // Prioritize active events (upcoming, ongoing, slot_penuh) first, finished at the bottom
        $events = $query
            ->orderByRaw("CASE WHEN status IN ('upcoming', 'ongoing', 'slot_penuh') THEN 0 ELSE 1 END ASC")
            ->orderByRaw("CASE WHEN status IN ('upcoming', 'ongoing', 'slot_penuh') THEN event_date ELSE NULL END ASC")
            ->orderByRaw("CASE WHEN status = 'finished' THEN event_date ELSE NULL END DESC")
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        $events->getCollection()->transform(function (Event $event) {
            $totalAccounts = (int) ($event->total_accounts_count ?? 0);
            $successAccounts = (int) ($event->success_accounts_count ?? 0);

            $event->setAttribute('total_accounts', $totalAccounts);
            $event->setAttribute('success_accounts', $successAccounts);
            $event->setAttribute('success_rate', $totalAccounts > 0
                ? round(($successAccounts / $totalAccounts) * 100, 1)
                : 0.0);
            $event->setAttribute('total_slots', $event->resolved_total_slots);
            $event->setAttribute('available_slots', $event->resolved_available_slots);

            return $event;
        });

        return Inertia::render('Public/Events/Index', [
            'events'  => $events,
            'filters' => [
                'q' => $request->get('q', ''),
            ],
        ]);
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

        $totalSlots = $event->resolved_total_slots;
        $availableSlots = $event->resolved_available_slots;

        // Per-event statistics (used when event is finished)
        $totalAccounts = Order::where('event_id', $event->id)
            ->distinct('email')
            ->count('email');

        $successAccounts = Order::where('event_id', $event->id)
            ->where('order_status', 'success')
            ->distinct('email')
            ->count('email');

        $eventStats = [
            'total_accounts'   => $totalAccounts,
            'success_accounts' => $successAccounts,
            'success_rate'     => $totalAccounts > 0
                ? round(($successAccounts / $totalAccounts) * 100, 1)
                : 0.0,
        ];

        // Check active order for current device on this event
        $deviceToken = request()->cookie('device_token');
        $activeOrder = null;

        if ($deviceToken) {
            $activeOrder = Order::withoutGlobalScope(\App\Models\Scopes\HideUnlinkedOrdersScope::class)
                ->where('device_token', $deviceToken)
                ->where('event_id', $event->id)
                ->whereNotIn('order_status', ['cancelled', 'failed'])
                ->first();
        }

        return Inertia::render('Public/Events/Show', [
            'event'          => $event,
            'totalSlots'     => $totalSlots,
            'availableSlots' => $availableSlots,
            'eventStats'     => $eventStats,
            'activeOrder'    => $activeOrder,
            'fieldConfig'    => [
                'platformsWithTitle'         => \App\Models\Order::PLATFORMS_WITH_TITLE,
                'platformsWithGender'        => \App\Models\Order::PLATFORMS_WITH_GENDER,
                'platformsWithBirthDate'     => \App\Models\Order::PLATFORMS_WITH_BIRTH_DATE,
                'platformsWithCity'          => \App\Models\Order::PLATFORMS_WITH_CITY,
                'platformsWithPaymentMethod' => \App\Models\Order::PLATFORMS_WITH_PAYMENT_METHOD,
                'titleOptions'               => \App\Models\Order::TITLE_OPTIONS,
                'genderOptions'              => \App\Models\Order::GENDER_OPTIONS,
                'paymentMethodGroups'        => \App\Models\Order::PAYMENT_METHOD_GROUPS,
            ],
        ]);
    }
}
