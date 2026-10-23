@extends('layouts.app')
@section('title', 'Customers Management')
@section('page-title', 'Customers')

@section('content')
<!-- Page Header (Tritcal Style) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
            style="width: 44px; height: 44px; background: linear-gradient(135deg, #484646 0%, #3a3838 100%); font-size: 20px;">
            <i class="bi bi-people"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">Customers Management</h4>
                <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 11px; font-weight: 700;">
                    {{ $customers->total() }} Total Clients
                </span>
            </div>
            <p class="text-muted small mb-0">Manage customer accounts, contact numbers, NIC, addresses, and event booking histories.</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('customers.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg"></i> Add New Customer
        </a>
    </div>
</div>

<!-- Search & Filter Card (Tritcal Style) -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
            <i class="bi bi-funnel text-secondary"></i> Search & Filters
        </h6>
        @if(request()->anyFilled(['search', 'status', 'has_events', 'from_date', 'to_date']))
            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-link text-decoration-none text-danger p-0" style="font-size: 12px;">
                <i class="bi bi-x-circle me-1"></i> Clear Filters
            </a>
        @endif
    </div>

    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('customers.index') }}" id="customersFilterForm">
            <div class="row g-3">
                <!-- 1. Search Keyword -->
                <div class="col-12 col-md-6 col-lg-4">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-search me-1"></i> Quick Search
                    </label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name, Phone, Email, NIC, Address...">
                </div>

                <!-- 2. Status Filter -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-toggle2-on me-1"></i> Status
                    </label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="1" @selected(request('status') === '1' || request('status') === 'active')>Active</option>
                        <option value="0" @selected(request('status') === '0' || request('status') === 'inactive' || request('status') === 'deactivated')>Deactivated</option>
                    </select>
                </div>

                <!-- 3. Event Booking Activity -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-calendar-check me-1"></i> Bookings Activity
                    </label>
                    <select name="has_events" class="form-select">
                        <option value="">All Clients</option>
                        <option value="yes" @selected(request('has_events') === 'yes')>With Registered Events</option>
                        <option value="no" @selected(request('has_events') === 'no')>Without Events Yet</option>
                    </select>
                </div>

                <!-- 4. From Date -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-calendar-date me-1"></i> Registered From
                    </label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>

                <!-- 5. To Date -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-calendar-date-fill me-1"></i> Registered To
                    </label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>

                <!-- 6. Filter & Reset Buttons -->
                <div class="col-12 col-lg-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary shadow-sm flex-grow-1" style="max-width: 200px;">
                        <i class="bi bi-funnel-fill me-1"></i> Filter Customers
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table Card -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden; border: 1px solid #edf2f7 !important;">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
            <i class="bi bi-list-check text-secondary"></i> Customer Accounts
        </h6>
        <div class="text-muted small">
            @if($customers->total() > 0)
                Showing <strong>{{ $customers->firstItem() }}-{{ $customers->lastItem() }}</strong> of <strong>{{ $customers->total() }}</strong> records
            @else
                0 records found
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="border-collapse: separate; border-spacing: 0;">
            <thead class="table-light text-muted text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                <tr>
                    <th class="ps-4 py-3">Customer</th>
                    <th class="py-3">Contact Information</th>
                    <th class="py-3">Address / NIC</th>
                    <th class="py-3 text-center">Bookings</th>
                    <th class="py-3 text-center">Status</th>
                    <th class="py-3">Registered Date</th>
                    <th class="py-3 text-end pe-4" style="min-width: 155px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <!-- Customer Name & Initial -->
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0"
                                    style="width: 36px; height: 36px; background: linear-gradient(135deg, #484646 0%, #3a3838 100%); font-size: 14px;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                        <a href="{{ route('customers.show', $customer) }}" class="text-decoration-none text-dark" target="_blank">
                                            {{ $customer->name }}
                                        </a>
                                    </div>
                                    @if($customer->company_name)
                                        <div class="text-muted text-truncate" style="font-size: 11.5px; max-width: 180px;" title="{{ $customer->company_name }}">
                                            <i class="bi bi-building me-1 text-secondary"></i> {{ $customer->company_name }}
                                        </div>
                                    @endif
                                    <small class="text-muted" style="font-size: 11px;">
                                        ID #{{ $customer->id }}
                                    </small>
                                </div>
                            </div>
                        </td>

                        <!-- Contact Details -->
                        <td>
                            @if($customer->phone)
                                <div class="text-dark small fw-medium">
                                    <i class="bi bi-telephone me-1 text-primary"></i>
                                    <a href="tel:{{ $customer->phone }}" class="text-decoration-none text-dark">
                                        {{ $customer->phone }}
                                    </a>
                                </div>
                            @endif
                            @if($customer->phone2)
                                <div class="text-muted small" style="font-size: 11px;">
                                    <i class="bi bi-whatsapp me-1 text-success"></i>
                                    {{ $customer->phone2 }}
                                </div>
                            @endif
                            @if($customer->email)
                                <div class="text-secondary small mt-0.5" style="font-size: 11.5px;">
                                    <i class="bi bi-envelope me-1 text-muted"></i>
                                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none text-muted">
                                        {{ $customer->email }}
                                    </a>
                                </div>
                            @endif
                            @if(!$customer->phone && !$customer->email)
                                <span class="text-muted small">No contact saved</span>
                            @endif
                        </td>

                        <!-- Address & NIC -->
                        <td>
                            @if($customer->address)
                                <div class="text-dark small text-truncate" style="max-width: 220px;" title="{{ $customer->address }}">
                                    <i class="bi bi-geo-alt me-1 text-secondary"></i> {{ $customer->address }}
                                </div>
                            @endif
                            @if($customer->city)
                                <div class="text-muted small mt-0.5" style="font-size: 11px;">
                                    <i class="bi bi-pin-map me-1 text-secondary"></i> {{ $customer->city }}
                                </div>
                            @endif
                            @if($customer->nic)
                                <div class="mt-0.5">
                                    <span class="badge bg-light text-secondary border font-monospace px-1.5 py-0.5" style="font-size: 10.5px;">
                                        NIC: {{ $customer->nic }}
                                    </span>
                                </div>
                            @endif
                            @if(!$customer->address && !$customer->city && !$customer->nic)
                                <span class="text-muted small">—</span>
                            @endif
                        </td>

                        <!-- Bookings Summary (Events & Orders count) -->
                        <td class="text-center">
                            <div class="d-inline-flex flex-column gap-1 align-items-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 11px;">
                                    <i class="bi bi-calendar-event me-1"></i> {{ $customer->events_count }} Events
                                </span>
                                @if($customer->orders_count > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 11px;">
                                        <i class="bi bi-receipt me-1"></i> {{ $customer->orders_count }} Orders
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            @if($customer->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 11px; font-weight: 600;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block; margin-right: 4px;"></span> Active
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="font-size: 11px; font-weight: 600;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444; display: inline-block; margin-right: 4px;"></span> Deactivated
                                </span>
                            @endif
                        </td>

                        <!-- Registered Date -->
                        <td>
                            <div class="text-dark small fw-medium">
                                {{ $customer->created_at?->format('M d, Y') ?? 'N/A' }}
                            </div>
                            <small class="text-muted" style="font-size: 11px;">
                                {{ $customer->created_at?->diffForHumans() }}
                            </small>
                        </td>

                        <!-- Actions (2x2 Grid, 2 buttons per row, 70px x 28px) -->
                        <td class="text-end pe-4 text-nowrap">
                            <div class="d-inline-grid justify-content-end" style="display: inline-grid; grid-template-columns: repeat(2, 70px); gap: 4px;">
                                <!-- Row 1: View & Edit -->
                                <a href="{{ route('customers.show', $customer) }}" class="btn-action btn-action-view" style="width: 70px; height: 28px;" title="View Client Details" target="_blank">
                                    <i class="bi bi-eye"></i> <span>View</span>
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" class="btn-action btn-action-edit" style="width: 70px; height: 28px;" title="Edit Customer">
                                    <i class="bi bi-pencil"></i> <span>Edit</span>
                                </a>

                                <!-- Row 2: Status Action (Deactivate / Activate) -->
                                <form method="POST" action="{{ route('customers.toggle-status', $customer) }}" class="m-0 p-0" style="grid-column: span 2;" onsubmit="return confirm('Are you sure you want to {{ $customer->is_active ? 'deactivate' : 'activate' }} this customer?');">
                                    @csrf
                                    @method('PATCH')
                                    @if($customer->is_active)
                                        <button type="submit" class="btn-action btn-action-delete w-100" style="height: 28px;" title="Deactivate Customer">
                                            <i class="bi bi-slash-circle"></i> <span>Deactivate</span>
                                        </button>
                                    @else
                                        <button type="submit" class="btn-action btn-action-order w-100" style="height: 28px;" title="Activate Customer">
                                            <i class="bi bi-check-circle"></i> <span>Activate</span>
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            <div class="fw-semibold" style="font-size: 14px;">No customers match your search criteria</div>
                            <small class="text-muted">Try adjusting your keyword or status filters.</small>
                            <div class="mt-3">
                                <a href="{{ route('customers.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} clients
            </span>
            <div>
                {{ $customers->links() }}
            </div>
        </div>
    @endif
</div>
@endsection