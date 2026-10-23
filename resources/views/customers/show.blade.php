@extends('layouts.app')
@section('title', $customer->name . ' - Customer Profile')
@section('page-title', 'Customer Profile')

@section('content')
<!-- Customer Header Card -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <!-- Left Info & Avatar -->
            <div class="d-flex align-items-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">{{ $customer->name }}</h4>
                        @if($customer->company_name)
                            <span class="text-secondary small fw-medium">
                                <i class="bi bi-building me-1"></i>{{ $customer->company_name }}
                            </span>
                        @endif
                        <span class="badge bg-light text-secondary border px-2 py-0.5" style="font-size: 11px;">
                            ID #{{ $customer->id }}
                        </span>
                        @if($customer->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 11px; font-weight: 600;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block; margin-right: 4px;"></span> Active
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="font-size: 11px; font-weight: 600;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444; display: inline-block; margin-right: 4px;"></span> Deactivated
                            </span>
                        @endif
                    </div>
                    <div class="text-muted small">
                        <i class="bi bi-calendar3 me-1"></i> Customer since {{ $customer->created_at?->format('M d, Y') ?? 'N/A' }} 
                        @if($customer->created_at)
                            <span class="mx-1">•</span> {{ $customer->created_at->diffForHumans() }}
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i> Edit Customer
                </a>
                <a href="{{ route('events.create') }}" class="btn btn-sm btn-primary shadow-sm" target="_blank">
                    <i class="bi bi-plus-lg me-1"></i> Book New Event
                </a>
                <form method="POST" action="{{ route('customers.toggle-status', $customer) }}" class="d-inline m-0 p-0" 
                    onsubmit="return confirm('Are you sure you want to {{ $customer->is_active ? 'deactivate' : 'activate' }} this customer?');">
                    @csrf
                    @method('PATCH')
                    @if($customer->is_active)
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Deactivate Customer">
                            <i class="bi bi-slash-circle me-1"></i> Deactivate
                        </button>
                    @else
                        <button type="submit" class="btn btn-sm btn-outline-success" title="Activate Customer">
                            <i class="bi bi-check-circle me-1"></i> Activate
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div>
                <p class="stat-label">Total Events</p>
                <p class="stat-value">{{ $customer->events->count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-receipt"></i>
            </div>
            <div>
                <p class="stat-label">Total Billed</p>
                <p class="stat-value">Rs. {{ number_format($totalBilled, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <p class="stat-label">Total Paid</p>
                <p class="stat-value text-success">Rs. {{ number_format($totalPaid, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <p class="stat-label">Outstanding Balance</p>
                <p class="stat-value {{ $totalBalance > 0 ? 'text-danger' : 'text-success' }}">
                    Rs. {{ number_format($totalBalance, 2) }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Contact & Information Card (4 cols) -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
                    <i class="bi bi-person-lines-fill text-primary"></i> Contact & Details
                </h6>
                <a href="{{ route('customers.edit', $customer) }}" class="text-decoration-none text-muted small" title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
            <div class="card-body p-3 p-md-4">
                <!-- Phone -->
                <div class="mb-3 pb-2 border-bottom">
                    <small class="text-muted d-block mb-1 font-monospace text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Primary Phone</small>
                    <div class="fw-semibold text-dark">
                        @if($customer->phone)
                            <a href="tel:{{ $customer->phone }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-telephone text-primary"></i> {{ $customer->phone }}
                            </a>
                        @else
                            <span class="text-muted small fst-italic">No phone provided</span>
                        @endif
                    </div>
                </div>

                <!-- Alternate Phone / WhatsApp -->
                @if($customer->phone2)
                    <div class="mb-3 pb-2 border-bottom">
                        <small class="text-muted d-block mb-1 font-monospace text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Alternate / WhatsApp</small>
                        <div class="fw-semibold text-dark">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone2) }}" target="_blank" class="text-decoration-none text-success d-flex align-items-center gap-2">
                                <i class="bi bi-whatsapp"></i> {{ $customer->phone2 }}
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Email -->
                <div class="mb-3 pb-2 border-bottom">
                    <small class="text-muted d-block mb-1 font-monospace text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Email Address</small>
                    <div class="fw-semibold text-dark">
                        @if($customer->email)
                            <a href="mailto:{{ $customer->email }}" class="text-decoration-none text-secondary d-flex align-items-center gap-2 text-truncate" style="font-size: 13.5px;">
                                <i class="bi bi-envelope text-muted"></i> {{ $customer->email }}
                            </a>
                        @else
                            <span class="text-muted small fst-italic">No email provided</span>
                        @endif
                    </div>
                </div>

                <!-- NIC -->
                <div class="mb-3 pb-2 border-bottom">
                    <small class="text-muted d-block mb-1 font-monospace text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">NIC / ID Number</small>
                    <div class="fw-semibold text-dark font-monospace" style="font-size: 13.5px;">
                        @if($customer->nic)
                            <i class="bi bi-card-text text-muted me-1"></i> {{ $customer->nic }}
                        @else
                            <span class="text-muted small fst-italic font-sans-serif">Not recorded</span>
                        @endif
                    </div>
                </div>

                <!-- Address & Location -->
                <div class="mb-3 pb-2 border-bottom">
                    <small class="text-muted d-block mb-1 font-monospace text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Address & City</small>
                    <div class="text-dark small">
                        @if($customer->address || $customer->city)
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-geo-alt text-danger mt-0.5"></i>
                                <div>
                                    @if($customer->address)
                                        <div>{{ $customer->address }}</div>
                                    @endif
                                    @if($customer->city || $customer->postal_code)
                                        <div class="text-muted mt-0.5">
                                            {{ implode(', ', array_filter([$customer->city, $customer->postal_code])) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <span class="text-muted small fst-italic">No address provided</span>
                        @endif
                    </div>
                </div>

                <!-- Birthday / Anniversary -->
                @if($customer->dob)
                    <div class="mb-3 pb-2 border-bottom">
                        <small class="text-muted d-block mb-1 font-monospace text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Birthday / Anniversary</small>
                        <div class="fw-semibold text-dark small d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-heart text-danger"></i> {{ $customer->dob->format('M d, Y') }}
                        </div>
                    </div>
                @endif

                <!-- Acquisition / Lead Source -->
                @if($customer->lead_source)
                    <div class="mb-3 pb-2 border-bottom">
                        <small class="text-muted d-block mb-1 font-monospace text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Acquisition Source</small>
                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 11px;">
                            <i class="bi bi-funnel me-1"></i> {{ $customer->lead_source }}
                        </span>
                    </div>
                @endif

                <!-- Notes / Preferences -->
                @if($customer->notes)
                    <div class="pt-1">
                        <small class="text-muted d-block mb-1 font-monospace text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Notes & Preferences</small>
                        <div class="p-2.5 rounded-2 bg-light border text-secondary small" style="white-space: pre-line; font-size: 12px; line-height: 1.5;">
                            {{ $customer->notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Events & Orders (8 cols) -->
    <div class="col-12 col-lg-8">
        <!-- 1. Customer Events Card -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary" style="width: 24px; height: 24px; font-size: 12px;">
                        <i class="bi bi-calendar-check"></i>
                    </span>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 13.5px;">Booked Events</h6>
                    <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 11px;">
                        {{ $customer->events->count() }}
                    </span>
                </div>
                <a href="{{ route('events.create') }}" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size: 12px;">
                    <i class="bi bi-plus-lg me-0.5"></i> Add Event
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light" style="font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-3 py-2.5">Event No / Name</th>
                            <th class="py-2.5">Package</th>
                            <th class="py-2.5">Date & Venue</th>
                            <th class="py-2.5 text-end">Amount</th>
                            <th class="py-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->events as $event)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark" style="font-size: 13px;">
                                        <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark">
                                            {{ $event->event_name }}
                                        </a>
                                    </div>
                                    <small class="text-muted font-monospace" style="font-size: 11px;">
                                        {{ $event->event_no }}
                                    </small>
                                </td>
                                <td>
                                    <div class="small fw-medium text-dark">
                                        {{ $event->subCategory->name ?? 'Custom Package' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-medium text-dark">
                                        <i class="bi bi-calendar-date me-1 text-muted"></i>
                                        {{ $event->event_date ? $event->event_date->format('M d, Y') : '—' }}
                                    </div>
                                    @if($event->venue)
                                        <small class="text-muted d-block text-truncate" style="max-width: 150px;" title="{{ $event->venue }}">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold text-dark small">
                                        Rs. {{ number_format((float)$event->total_amount, 2) }}
                                    </div>
                                    @if((float)$event->balance > 0)
                                        <small class="text-danger fw-semibold" style="font-size: 10.5px;">
                                            Bal: Rs. {{ number_format((float)$event->balance, 2) }}
                                        </small>
                                    @else
                                        <small class="text-success fw-semibold" style="font-size: 10.5px;">
                                            Paid Full
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($event->status === 'confirmed')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 11px;">
                                            Confirmed
                                        </span>
                                    @elseif($event->status === 'completed')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-0.5" style="font-size: 11px;">
                                            Completed
                                        </span>
                                    @elseif($event->status === 'cancelled')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5" style="font-size: 11px;">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5" style="font-size: 11px;">
                                            {{ ucfirst($event->status ?? 'Draft') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-x fs-3 d-block mb-1 text-secondary opacity-50"></i>
                                    No events registered for this customer yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Customer Orders Card -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success" style="width: 24px; height: 24px; font-size: 12px;">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 13.5px;">Customer Orders</h6>
                    <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 11px;">
                        {{ $customer->orders->count() }}
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light" style="font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-3 py-2.5">Order No</th>
                            <th class="py-2.5">Order Date</th>
                            <th class="py-2.5 text-center">Status</th>
                            <th class="py-2.5 text-end pe-3">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders as $order)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('orders.show', $order) }}" class="fw-bold text-dark text-decoration-none" style="font-size: 13px;">
                                        {{ $order->order_no }}
                                    </a>
                                </td>
                                <td>
                                    <span class="small text-muted">{{ $order->order_date?->format('M j, Y') ?? '—' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-status badge-{{ $order->payment_status }}">
                                        <i class="bi bi-dot"></i> {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3 fw-bold small {{ $order->balance > 0 ? 'text-danger' : 'text-success' }}">
                                    Rs. {{ number_format($order->balance, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-1 text-secondary opacity-50"></i>
                                    No orders recorded for this customer yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection