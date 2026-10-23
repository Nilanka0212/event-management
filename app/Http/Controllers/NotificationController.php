<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $events7Days = Event::with(['customer', 'subCategory', 'orders.payments'])
            ->whereDate('event_date', $today->copy()->addDays(7))
            ->where('status', '!=', 'cancelled')
            ->get();

        $events3Days = Event::with(['customer', 'subCategory', 'orders.payments'])
            ->whereDate('event_date', $today->copy()->addDays(3))
            ->where('status', '!=', 'cancelled')
            ->get();

        $upcomingEvents = Event::with(['customer', 'subCategory', 'orders.payments'])
            ->where('event_date', '>=', $today)
            ->where('status', '!=', 'cancelled')
            ->orderBy('event_date')
            ->paginate(25)
            ->withQueryString();

        return view('notifications.index', compact('events7Days', 'events3Days', 'upcomingEvents'));
    }

    public function markRead($id)
    {
        // Mark notification as read by updating event flag
        $type = request('type'); // '7' or '3'
        $event = Event::findOrFail($id);
        if ($type === '7') {
            $event->update(['notification_7_sent' => true]);
        } elseif ($type === '3') {
            $event->update(['notification_3_sent' => true]);
        }
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        $today = Carbon::today();
        Event::whereDate('event_date', $today->copy()->addDays(7))->update(['notification_7_sent' => true]);
        Event::whereDate('event_date', $today->copy()->addDays(3))->update(['notification_3_sent' => true]);
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
