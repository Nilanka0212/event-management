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
        $query = Event::with(['customer', 'subCategory.category'])
            ->where('status', '!=', 'cancelled');

        if ($request->filled('start') && $request->filled('end')) {
            try {
                $start = Carbon::parse($request->get('start'))->format('Y-m-d');
                $end = Carbon::parse($request->get('end'))->format('Y-m-d');
                $query->whereBetween('event_date', [$start, $end]);
            } catch (\Exception $e) {
                // If parsing fails, fall back to current month window
                $query->whereBetween('event_date', [
                    Carbon::now()->subMonths(2)->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->addMonths(2)->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        $events = $query->orderBy('event_date', 'asc')
            ->get()
            ->map(function ($event) {
                $color = match ($event->status) {
                    'pending' => '#f59e0b',
                    'confirmed' => '#3b82f6',
                    'completed' => '#10b981',
                    default => '#6b7280',
                };
                $customerName = $event->customer?->name ?? 'Walk-in / N/A';
                return [
                    'id' => $event->id,
                    'title' => $event->event_name . ' (' . $customerName . ')',
                    'start' => $event->event_date->format('Y-m-d'),
                    'color' => $color,
                    'url' => route('events.show', $event->id),
                    'extendedProps' => [
                        'event_name' => $event->event_name,
                        'customer' => $customerName,
                        'package' => $event->subCategory->name ?? '',
                        'category' => $event->subCategory->category->name ?? '',
                        'status' => $event->status,
                        'venue' => $event->venue ?: 'Venue TBD',
                        'event_no' => $event->event_no,
                        'total_amount' => (float) ($event->total_amount ?? 0),
                        'order_url' => route('events.create-order', $event->id),
                    ]
                ];
            });

        return response()->json($events);
    }
}
