<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function getEvents(Request $request)
    {
        $start = $request->get('start', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end = $request->get('end', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $events = Event::with(['customer', 'subCategory.category'])
            ->whereBetween('event_date', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function ($event) {
                $color = match($event->status) {
                    'pending' => '#f59e0b',
                    'confirmed' => '#3b82f6',
                    'completed' => '#10b981',
                    default => '#6b7280',
                };
                return [
                    'id' => $event->id,
                    'title' => $event->event_name . ' - ' . $event->customer->name,
                    'start' => $event->event_date->format('Y-m-d'),
                    'color' => $color,
                    'url' => route('events.show', $event->id),
                    'extendedProps' => [
                        'customer' => $event->customer->name,
                        'package' => $event->subCategory->name ?? '',
                        'category' => $event->subCategory->category->name ?? '',
                        'status' => $event->status,
                        'venue' => $event->venue,
                        'event_no' => $event->event_no,
                    ]
                ];
            });

        return response()->json($events);
    }
}
