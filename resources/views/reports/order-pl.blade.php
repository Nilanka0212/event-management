@extends('layouts.app')
@section('title', 'Order Profit & Loss')
@section('page-title', 'Order Profit & Loss')

@section('content')
<div class="page-header">
    <div>
        <h4>Order Profit & Loss</h4>
        <p>Revenue and estimated event costs analyzed by individual order.</p>
    </div>
</div>

<!-- Filters Card -->
<div class="card mb-3">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="date_from">From Date</label>
                <input class="form-control" type="date" id="date_from" name="date_from" value="{{ $dateFrom }}">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="date_to">To Date</label>
                <input class="form-control" type="date" id="date_to" name="date_to" value="{{ $dateTo }}">
            </div>
            <div class="col-12 col-sm-auto d-flex align-items-center gap-2 mt-2 mt-sm-0">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel-fill me-1"></i> Apply Filter
                </button>
                @if(request()->hasAny(['date_from', 'date_to']))
                    <a href="{{ route('reports.order-pl') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards Row -->
<div class="row g-3 mb-3">
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <p class="stat-label">Total Revenue</p>
                <p class="stat-value text-success">Rs. {{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="bi bi-credit-card-2-front"></i>
            </div>
            <div>
                <p class="stat-label">Total Estimated Cost</p>
                <p class="stat-value text-danger">Rs. {{ number_format($totalCost, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-icon amber">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
                <p class="stat-label">Net Profit</p>
                <p class="stat-value text-dark">Rs. {{ number_format($totalProfit, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Event</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Cost</th>
                    <th class="text-end">Profit</th>
                    <th class="text-end">Margin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $row)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $row['order']) }}" class="fw-bold">
                                {{ $row['order']->order_no }}
                            </a>
                        </td>
                        <td>{{ $row['order']->customer?->name ?? '—' }}</td>
                        <td>{{ $row['order']->event?->event_name ?? '—' }}</td>
                        <td class="text-end fw-semibold text-dark">Rs. {{ number_format($row['revenue'], 2) }}</td>
                        <td class="text-end text-danger">Rs. {{ number_format($row['cost'], 2) }}</td>
                        <td class="text-end text-success fw-bold">Rs. {{ number_format($row['profit'], 2) }}</td>
                        <td class="text-end">
                            <span class="badge {{ $row['margin'] >= 20 ? 'bg-success-subtle text-success border border-success-subtle' : ($row['margin'] >= 0 ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle') }} px-2 py-1">
                                {{ number_format($row['margin'], 1) }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            No orders recorded in this period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection