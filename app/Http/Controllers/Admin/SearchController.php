<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use App\Models\OrderGuest;
use App\Models\PaymentLog;
use App\Models\SuccessLog;
use App\Services\MaskService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q       = $request->get('q', '');
        $tab     = $request->get('tab', 'all');
        $results = [];

        if (strlen($q) >= 2) {
            $results['orders'] = Order::with(['event', 'salePhase', 'ticketCategory'])
                ->where(function ($query) use ($q) {
                    $query->where('order_code', 'like', "%{$q}%")
                          ->orWhere('full_name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%")
                          ->orWhere('phone_number', 'like', "%{$q}%")
                          ->orWhere('telegram_username', 'like', "%{$q}%");
                })
                ->latest()
                ->limit(20)
                ->get();

            $results['events'] = Event::where('title', 'like', "%{$q}%")
                ->orWhere('artist_name', 'like', "%{$q}%")
                ->orWhere('city', 'like', "%{$q}%")
                ->limit(10)
                ->get();

            $results['payments'] = PaymentLog::with('order')
                ->where('payment_reference', 'like', "%{$q}%")
                ->limit(10)
                ->get();

            $results['logs'] = SuccessLog::with(['event', 'salePhase', 'ticketCategory'])
                ->where('email', 'like', "%{$q}%")
                ->orWhere('username', 'like', "%{$q}%")
                ->limit(10)
                ->get();
        }

        return view('admin.search.index', compact('q', 'tab', 'results'));
    }
}