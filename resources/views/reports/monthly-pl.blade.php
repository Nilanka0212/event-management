@extends('layouts.app')
@section('title', 'Monthly Profit & Loss')
@section('page-title', 'Monthly Profit & Loss')

@section('content')
<div class="page-header">
    <div>
        <h4>Monthly Profit & Loss</h4>
        <p>Comparative overview of payments received against recorded company expenses by month.</p>
    </div>
</div>

<!-- Filters Card -->
<div class="card mb-3">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="year">Financial Year</label>
                <input class="form-control" type="number" id="year" name="year" value="{{ $year }}" min="2000" max="2100">
            </div>
            <div class="col-12 col-sm-auto d-flex align-items-center gap-2 mt-2 mt-sm-0">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel-fill me-1"></i> Apply
                </button>
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
                <p class="stat-label">Total Revenue ({{ $year }})</p>
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
                <p class="stat-label">Total Expenses ({{ $year }})</p>
                <p class="stat-value text-danger">Rs. {{ number_format($totalExpenses, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-icon amber">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
                <p class="stat-label">Net Profit ({{ $year }})</p>
                <p class="stat-value text-dark">Rs. {{ number_format($totalProfit, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Table Card -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Month</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Expenses</th>
                    <th class="text-end">Net Profit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($months as $month)
                    <tr>
                        <td class="fw-semibold text-dark">{{ $month['month'] }}</td>
                        <td class="text-end text-success fw-medium">Rs. {{ number_format($month['revenue'], 2) }}</td>
                        <td class="text-end text-danger fw-medium">Rs. {{ number_format($month['expenses'], 2) }}</td>
                        <td class="text-end fw-bold {{ $month['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Rs. {{ number_format($month['profit'], 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection