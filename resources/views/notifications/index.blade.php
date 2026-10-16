@extends('layouts.app')
@section('title', 'Event reminders')
@section('page-title', 'Event reminders')
@section('content')
<div class="page-header"><div><h4>Event reminders</h4><p class="text-muted mb-0">Events approaching in 3 or 7 days.</p></div><form method="POST" action="{{ route('notifications.mark-all-read') }}">@csrf<button class="btn btn-outline-secondary"><i class="bi bi-check2-all me-1"></i> Mark all read</button></form></div>
<div class="row g-3 mb-4">
    @foreach([['7-day reminders', $events7Days, '7'], ['3-day reminders', $events3Days, '3']] as [$title, $events, $type])
        <div class="col-lg-6"><div class="card h-100"><div class="card-header d-flex justify-content-between"><h6>{{ $title }}</h6><span class="badge text-bg-warning">{{ $events->count() }}</span></div><div class="list-group list-group-flush">
            @forelse($events as $event)<div class="list-group-item d-flex justify-content-between align-items-center gap-3"><div><a href="{{ route('events.show', $event) }}" class="fw-semibold">{{ $event->event_name }}</a><div class="small text-muted">{{ $event->customer?->name }} · {{ $event->event_date?->format('M j, Y') }}</div></div><form method="POST" action="{{ route('notifications.mark-read', $event->id) }}">@csrf<input type="hidden" name="type" value="{{ $type }}"><button class="btn btn-sm btn-outline-secondary">Dismiss</button></form></div>
            @empty<div class="text-center text-muted py-4">No reminders for this period.</div>@endforelse
        </div></div></div>
    @endforeach
</div>
<div class="card"><div class="card-header"><h6>Upcoming events</h6></div><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Event</th><th>Customer</th><th>Package</th><th>Date</th><th>Venue</th></tr></thead><tbody>@forelse($upcomingEvents as $event)<tr><td><a href="{{ route('events.show', $event) }}">{{ $event->event_name }}</a></td><td>{{ $event->customer?->name }}</td><td>{{ $event->subCategory?->name }}</td><td>{{ $event->event_date?->format('M j, Y') }}</td><td>{{ $event->venue ?: 'N/A' }}</td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-4">No upcoming events.</td></tr>@endforelse</tbody></table></div></div>
@endsection