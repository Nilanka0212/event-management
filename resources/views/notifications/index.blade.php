@extends('layouts.app')
@section('title', 'Event Notifications & Reminders')
@section('page-title', 'Event Notifications & Reminders')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1" style="color: #0f172a;">Event Reminders & Order Alerts</h4>
        <p class="text-muted small mb-0">System automatically tracks events due in <strong>7 days</strong> and <strong>3 days</strong>. Click an alert to open/create the customer order.</p>
    </div>
    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-check2-all"></i>
            <span>Mark All Reminders as Read</span>
        </button>
    </form>
</div>

<!-- 7-Day & 3-Day Notification Cards -->
<div class="row g-4 mb-4">
    <!-- 7-Day Reminders -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0 border-top border-4 border-info">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-info-subtle text-info border border-info-subtle p-2 rounded-circle">
                        <i class="bi bi-clock-history fs-6"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">7-Day Event Reminders</h6>
                        <small class="text-muted">Events happening exactly 7 days from today</small>
                    </div>
                </div>
                <span class="badge bg-info text-white rounded-pill px-3 py-1">{{ $events7Days->count() }}</span>
            </div>

            <div class="list-group list-group-flush">
                @forelse($events7Days as $event)
                    @php
                        $hasOrder = $event->orders->isNotEmpty();
                        $targetUrl = $hasOrder ? route('orders.show', $event->orders->first()) : route('events.create-order', $event);
                    @endphp
                    <div class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center gap-3">
                        <a href="{{ $targetUrl }}" class="text-decoration-none text-dark flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-primary">{{ $event->event_name }}</span>
                                @if(!$hasOrder)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 10.5px;">
                                        <i class="bi bi-exclamation-circle me-1"></i> No Order Yet - Click to Create
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 10.5px;">
                                        <i class="bi bi-check-circle me-1"></i> Order #{{ $event->orders->first()->order_no }}
                                    </span>
                                @endif
                            </div>
                            <div class="small text-muted d-flex flex-wrap gap-3">
                                <span><i class="bi bi-person me-1"></i>{{ $event->customer?->name }}</span>
                                <span><i class="bi bi-calendar-event me-1"></i>{{ $event->event_date?->format('M d, Y') }}</span>
                                <span><i class="bi bi-box-seam me-1"></i>{{ $event->subCategory?->name }}</span>
                            </div>
                        </a>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ $targetUrl }}" class="btn btn-sm {{ $hasOrder ? 'btn-outline-primary' : 'btn-warning text-dark fw-semibold' }}">
                                <i class="bi {{ $hasOrder ? 'bi-eye' : 'bi-plus-circle-fill' }} me-1"></i>
                                {{ $hasOrder ? 'View Order' : 'Create Order' }}
                            </a>
                            <form method="POST" action="{{ route('notifications.mark-read', $event->id) }}">
                                @csrf
                                <input type="hidden" name="type" value="7">
                                <button type="submit" class="btn btn-sm btn-light border text-muted" title="Dismiss">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary"></i>
                        No events due in 7 days.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 3-Day Reminders -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0 border-top border-4 border-danger">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle p-2 rounded-circle">
                        <i class="bi bi-exclamation-diamond-fill fs-6"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">3-Day Urgent Reminders</h6>
                        <small class="text-muted">Events happening in 3 days &bull; Immediate preparation</small>
                    </div>
                </div>
                <span class="badge bg-danger text-white rounded-pill px-3 py-1">{{ $events3Days->count() }}</span>
            </div>

            <div class="list-group list-group-flush">
                @forelse($events3Days as $event)
                    @php
                        $hasOrder = $event->orders->isNotEmpty();
                        $targetUrl = $hasOrder ? route('orders.show', $event->orders->first()) : route('events.create-order', $event);
                    @endphp
                    <div class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center gap-3">
                        <a href="{{ $targetUrl }}" class="text-decoration-none text-dark flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-danger">{{ $event->event_name }}</span>
                                @if(!$hasOrder)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 10.5px;">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Order Missing - Click to Create
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 10.5px;">
                                        <i class="bi bi-check-circle me-1"></i> Order #{{ $event->orders->first()->order_no }}
                                    </span>
                                @endif
                            </div>
                            <div class="small text-muted d-flex flex-wrap gap-3">
                                <span><i class="bi bi-person me-1"></i>{{ $event->customer?->name }}</span>
                                <span><i class="bi bi-calendar-event me-1"></i>{{ $event->event_date?->format('M d, Y') }}</span>
                                <span><i class="bi bi-box-seam me-1"></i>{{ $event->subCategory?->name }}</span>
                            </div>
                        </a>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ $targetUrl }}" class="btn btn-sm {{ $hasOrder ? 'btn-outline-primary' : 'btn-danger text-white fw-semibold' }}">
                                <i class="bi {{ $hasOrder ? 'bi-eye' : 'bi-plus-circle-fill' }} me-1"></i>
                                {{ $hasOrder ? 'View Order' : 'Create Order' }}
                            </a>
                            <form method="POST" action="{{ route('notifications.mark-read', $event->id) }}">
                                @csrf
                                <input type="hidden" name="type" value="3">
                                <button type="submit" class="btn btn-sm btn-light border text-muted" title="Dismiss">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary"></i>
                        No urgent events due in 3 days.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Events Timeline Table -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar-week text-primary me-2"></i>Upcoming Events Schedule</h6>
            <small class="text-muted">All active scheduled events approaching in the calendar</small>
        </div>
        <a href="{{ route('calendar') }}" class="btn btn-outline-secondary">
            <i class="bi bi-calendar3 me-1"></i> View Full Calendar
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Event</th>
                    <th>Customer</th>
                    <th>Package</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Order Status</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($upcomingEvents as $event)
                    @php
                        $hasOrder = $event->orders->isNotEmpty();
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('events.show', $event) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                {{ $event->event_name }}
                            </a>
                            <div class="text-muted small">{{ $event->event_no }}</div>
                        </td>
                        <td>{{ $event->customer?->name }}</td>
                        <td>{{ $event->subCategory?->name }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-calendar-check me-1 text-primary"></i>
                                {{ $event->event_date?->format('M d, Y') }}
                            </span>
                        </td>
                        <td>{{ $event->venue ?: '-' }}</td>
                        <td>
                            @if($hasOrder)
                                <a href="{{ route('orders.show', $event->orders->first()) }}" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none">
                                    <i class="bi bi-receipt me-1"></i> Order #{{ $event->orders->first()->order_no }}
                                </a>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                    <i class="bi bi-clock me-1"></i> Pending Order
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4 text-nowrap">
                            <div class="action-btns-wrapper justify-content-end">
                                @if(!$hasOrder)
                                    <a href="{{ route('events.create-order', $event) }}" class="btn-action btn-action-order" title="Create Order">
                                        <i class="bi bi-plus-lg"></i> <span>Order</span>
                                    </a>
                                @else
                                    <a href="{{ route('orders.show', $event->orders->first()) }}" class="btn-action btn-action-view" title="View Order">
                                        <i class="bi bi-receipt"></i> <span>Order</span>
                                    </a>
                                @endif
                                <a href="{{ route('events.show', $event) }}" class="btn-action btn-action-view" title="View Event">
                                    <i class="bi bi-eye"></i> <span>Event</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No upcoming events scheduled.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($upcomingEvents, 'hasPages') && $upcomingEvents->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">
                Showing {{ $upcomingEvents->firstItem() }} to {{ $upcomingEvents->lastItem() }} of {{ $upcomingEvents->total() }} events
            </span>
            <div>
                {{ $upcomingEvents->withQueryString()->links() }}
            </div>
        </div>
    @endif
</div>
@endsection