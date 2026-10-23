@extends('layouts.app')
@section('title', 'Transactions Report')
@section('page-title', 'Transactions Report')

@section('content')
<style>
    /* Premium KPI Stat Cards */
    .txn-stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 20px;
        border: 1px solid #edf2f7;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .txn-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.04);
    }
    .txn-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .txn-stat-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 3px;
        letter-spacing: 0.5px;
    }
    .txn-stat-value {
        font-size: 19px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.3px;
        line-height: 1.2;
    }

    /* Filter Card */
    .filter-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #edf2f7;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .quick-preset-btn {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .quick-preset-btn:hover, .quick-preset-btn.active {
        background: #484646;
        color: #ffffff;
        border-color: #484646;
    }

    /* Custom Table */
    .table-report {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .table-report th {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        background-color: #f8fafc;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        padding: 13px 16px;
        white-space: nowrap;
    }
    .table-report td {
        font-size: 13px;
        vertical-align: middle;
        padding: 13px 16px;
        border-bottom: 1px solid #f1f5f9;
        background-color: #ffffff;
    }
    .table-report tbody tr:hover td {
        background-color: #f8fafc;
    }
    .table-report tfoot td {
        background-color: #f8fafc !important;
        font-weight: 700;
        border-top: 2px solid #cbd5e1;
        padding: 14px 16px;
    }

    .badge-inflow {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-weight: 700;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }
    .badge-outflow {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
        font-weight: 700;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    /* Print Styles */
    @media print {
        .no-print, .sidebar, .topbar, .btn, .filter-card, .page-header-actions, .pagination {
            display: none !important;
        }
        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 12px;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .table-report th {
            background: #eeeeee !important;
            color: #000000 !important;
            border-bottom: 1px solid #000000 !important;
        }
        .table-report td {
            border-bottom: 1px solid #dddddd !important;
        }
        .print-header {
            display: block !important;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .print-signatures {
            display: flex !important;
            justify-content: space-between;
            margin-top: 50px;
            padding-top: 20px;
        }
    }
    .print-header, .print-signatures {
        display: none;
    }
</style>

<!-- Print Only Corporate Header -->
<div class="print-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0">COMPANY TRANSACTIONS AUDIT REPORT</h3>
            <p class="text-muted mb-0 small">Official transaction ledger covering cash, cheques, and electronic bank deposits/withdrawals</p>
        </div>
        <div class="text-end">
            <div class="fw-bold">Statement Period:</div>
            <div class="small">{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</div>
            <div class="text-muted small">Generated on: {{ now()->format('d M Y, h:i A') }}</div>
        </div>
    </div>
</div>

<!-- Page Header (Tritcal Style) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 no-print">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
            style="width: 44px; height: 44px; background: linear-gradient(135deg, #484646 0%, #3a3838 100%); font-size: 20px;">
            <i class="bi bi-arrow-left-right"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">Transactions Report</h4>
                <span class="badge bg-light text-secondary border px-2 py-0.5" style="font-size: 11px; font-weight: 700;">
                    Audited Ledger
                </span>
                <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 11px;">
                    {{ $transactions->total() }} Records
                </span>
            </div>
            <p class="text-muted small mb-0">Comprehensive financial movement audit of all counter cash, cheques, and bank ledger transfers.</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap page-header-actions">
        <a href="{{ route('cash-book') }}" class="btn btn-outline-secondary shadow-sm" title="View Cash Till Register">
            <i class="bi bi-journal-text me-1 text-success"></i> Cash Book
        </a>
        <a href="{{ route('bank-book') }}" class="btn btn-outline-secondary shadow-sm" title="View Bank Accounts Register">
            <i class="bi bi-bank me-1 text-primary"></i> Bank Book
        </a>
        <button type="button" class="btn btn-outline-secondary shadow-sm" onclick="window.print()" title="Print Transactions Statement">
            <i class="bi bi-printer me-1"></i> Print Statement
        </button>
        <a href="{{ route('bank-transactions.create') }}" class="btn btn-primary shadow-sm" target="_blank">
            <i class="bi bi-plus-lg me-1"></i> Record Transaction
        </a>
    </div>
</div>

<!-- ============================================================== -->
<!-- 1. KPI STAT CARDS OVERVIEW                                    -->
<!-- ============================================================== -->
<div class="row g-3 mb-4">
    <!-- Total Inflows / Deposits -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="txn-stat-card">
            <div class="txn-stat-icon" style="background-color: #ecfdf5; color: #059669;">
                <i class="bi bi-arrow-down-left-circle-fill"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="txn-stat-label text-success">Total Money In (Deposits)</div>
                <div class="txn-stat-value text-success text-truncate">
                    Rs. {{ number_format($periodIncomeTotal, 2) }}
                </div>
                <small class="text-muted d-block text-truncate mt-1" style="font-size: 11px;">
                    Cash: {{ number_format($cashInTotal, 2) }} &bull; Bank: {{ number_format($bankInTotal, 2) }}
                </small>
            </div>
        </div>
    </div>

    <!-- Total Outflows / Withdrawals -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="txn-stat-card">
            <div class="txn-stat-icon" style="background-color: #fff1f2; color: #e11d48;">
                <i class="bi bi-arrow-up-right-circle-fill"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="txn-stat-label text-danger">Total Money Out (Withdrawals)</div>
                <div class="txn-stat-value text-danger text-truncate">
                    Rs. {{ number_format($periodExpenseTotal, 2) }}
                </div>
                <small class="text-muted d-block text-truncate mt-1" style="font-size: 11px;">
                    Cash: {{ number_format($cashOutTotal, 2) }} &bull; Bank: {{ number_format($bankOutTotal, 2) }}
                </small>
            </div>
        </div>
    </div>

    <!-- Net Flow Position -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="txn-stat-card" style="border-left: 4px solid {{ $netFlow >= 0 ? '#10b981' : '#ef4444' }} !important;">
            <div class="txn-stat-icon" style="background-color: {{ $netFlow >= 0 ? '#eff6ff' : '#fef2f2' }}; color: {{ $netFlow >= 0 ? '#2563eb' : '#dc2626' }};">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="txn-stat-label">Net Financial Flow</div>
                <div class="txn-stat-value text-truncate {{ $netFlow >= 0 ? 'text-dark' : 'text-danger' }}">
                    {{ $netFlow >= 0 ? '+' : '' }} Rs. {{ number_format($netFlow, 2) }}
                </div>
                <small class="d-block text-truncate mt-1 fw-semibold {{ $netFlow >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 11px;">
                    <i class="bi {{ $netFlow >= 0 ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }} me-1"></i>
                    {{ $netFlow >= 0 ? 'Positive Surplus Movement' : 'Net Outflow Deficit' }}
                </small>
            </div>
        </div>
    </div>

    <!-- Total Count -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="txn-stat-card">
            <div class="txn-stat-icon" style="background-color: #f1f5f9; color: #334155;">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="txn-stat-label">Transactions Count</div>
                <div class="txn-stat-value text-dark text-truncate">
                    {{ $totalTransactionsCount }}
                </div>
                <small class="text-muted d-block text-truncate mt-1" style="font-size: 11px;">
                    In Period ({{ \Carbon\Carbon::parse($dateFrom)->format('d M') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M') }})
                </small>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 2. DATE RANGE & MULTI-FIELD FILTERS CARD                       -->
<!-- ============================================================== -->
<div class="card filter-card mb-4 no-print">
    <div class="card-body p-3.5">
        <form method="GET" action="{{ route('reports.transactions') }}" id="txnReportFilterForm">
            <!-- Quick Preset Date Buttons -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                    <span class="text-muted small fw-bold me-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Quick Presets:</span>
                    <button type="button" class="quick-preset-btn" onclick="applyPreset('today')">Today</button>
                    <button type="button" class="quick-preset-btn" onclick="applyPreset('this_week')">This Week</button>
                    <button type="button" class="quick-preset-btn" onclick="applyPreset('this_month')">This Month</button>
                    <button type="button" class="quick-preset-btn" onclick="applyPreset('last_month')">Last Month</button>
                    <button type="button" class="quick-preset-btn" onclick="applyPreset('this_year')">This Year</button>
                </div>
                <div class="text-muted small" style="font-size: 11.5px;">
                    <i class="bi bi-calendar3 me-1"></i> Filtered from <strong class="text-dark">{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</strong> to <strong class="text-dark">{{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</strong>
                </div>
            </div>

            <div class="row g-2.5 align-items-end">
                <!-- Date From -->
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label small fw-bold text-dark mb-1" for="date_from">From Date</label>
                    <input type="date" class="form-control form-control-sm" id="date_from" name="date_from" value="{{ $dateFrom }}">
                </div>

                <!-- Date To -->
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label small fw-bold text-dark mb-1" for="date_to">To Date</label>
                    <input type="date" class="form-control form-control-sm" id="date_to" name="date_to" value="{{ $dateTo }}">
                </div>

                <!-- Flow Type -->
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label small fw-bold text-dark mb-1" for="type_select">Flow Type</label>
                    <select class="form-select form-select-sm" name="type" id="type_select">
                        <option value="all" {{ ($type ?? 'all') === 'all' ? 'selected' : '' }}>All Flows</option>
                        <option value="income" {{ ($type ?? '') === 'income' ? 'selected' : '' }}>🟢 Deposits (Inflow)</option>
                        <option value="expense" {{ ($type ?? '') === 'expense' ? 'selected' : '' }}>🔴 Withdrawals (Outflow)</option>
                    </select>
                </div>

                <!-- Method -->
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label small fw-bold text-dark mb-1" for="method_select">Channel</label>
                    <select class="form-select form-select-sm" name="method" id="method_select">
                        <option value="all" {{ ($method ?? 'all') === 'all' ? 'selected' : '' }}>All Channels</option>
                        <option value="cash" {{ ($method ?? '') === 'cash' ? 'selected' : '' }}>💵 Cash</option>
                        <option value="bank_transfer" {{ ($method ?? '') === 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                        <option value="cheque" {{ ($method ?? '') === 'cheque' ? 'selected' : '' }}>📑 Cheque</option>
                    </select>
                </div>

                <!-- Bank Filter -->
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label small fw-bold text-dark mb-1" for="bank_select">Bank Account</label>
                    <select class="form-select form-select-sm" name="bank" id="bank_select">
                        <option value="">All Accounts</option>
                        @foreach($banks as $b)
                            <option value="{{ $b->bank_name }}" {{ ($bankFilter ?? '') == $b->bank_name ? 'selected' : '' }}>
                                {{ $b->bank_name }} ({{ $b->branch ?: 'Main' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search -->
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label small fw-bold text-dark mb-1" for="search_input">Keyword</label>
                    <input type="text" class="form-control form-control-sm" id="search_input" name="search" 
                           value="{{ $search ?? '' }}" placeholder="Ref, Cheque, Note...">
                </div>

                <!-- Filter Action Buttons -->
                <div class="col-12 d-flex justify-content-end gap-1.5 mt-2 pt-2 border-top">
                    @if(request()->hasAny(['date_from', 'date_to', 'type', 'method', 'bank', 'search']))
                        <a href="{{ route('reports.transactions') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters" style="height: 31px;">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-3" style="height: 31px;">
                        <i class="bi bi-funnel-fill me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 3. TRANSACTIONS REPORT DATA TABLE                              -->
<!-- ============================================================== -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 14px;">
                <i class="bi bi-table text-primary"></i> Transactions Ledger Statement
            </h6>
            <small class="text-muted" style="font-size: 11.5px;">
                Complete chronological listing of all money inflows and outflows.
            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border px-2.5 py-1 font-monospace" style="font-size: 11px;">
                Showing {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} Entries
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-report align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 130px;">Date & TXN</th>
                    <th>Particulars / Description</th>
                    <th style="width: 170px;">Channel & Account</th>
                    <th style="width: 120px;">Ref / Cheque</th>
                    <th class="text-center" style="width: 110px;">Type</th>
                    <th class="text-end" style="width: 150px;">Inflow (Deposit)</th>
                    <th class="text-end" style="width: 150px;">Outflow (Withdrawal)</th>
                    <th class="text-center no-print" style="width: 70px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <!-- Date & Transaction No -->
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 12.5px;">
                                {{ $transaction->transaction_date?->format('M d, Y') ?? '—' }}
                            </div>
                            <span class="badge bg-light text-secondary border font-monospace mt-0.5" style="font-size: 10px;">
                                {{ $transaction->transaction_no ?: 'TXN-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <!-- Particulars & Notes -->
                        <td>
                            <div class="fw-semibold text-dark" style="font-size: 13px; line-height: 1.3;">
                                {{ $transaction->description }}
                            </div>
                            @if($transaction->notes)
                                <small class="text-muted d-block mt-0.5" style="font-size: 11px;">
                                    <i class="bi bi-chat-left-text me-1"></i>{{ $transaction->notes }}
                                </small>
                            @endif
                        </td>

                        <!-- Channel & Bank -->
                        <td>
                            <div class="d-flex flex-column gap-0.5">
                                <div>
                                    @if($transaction->transaction_method === 'cash')
                                        <span class="badge bg-light text-dark border" style="font-size: 11px;">💵 Cash Till</span>
                                    @elseif($transaction->transaction_method === 'cheque')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning" style="font-size: 11px;">📑 Cheque</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info" style="font-size: 11px;">🏦 Bank Transfer</span>
                                    @endif
                                </div>
                                @if($transaction->bank_name)
                                    <small class="text-muted d-block text-truncate" style="font-size: 11px; max-width: 190px;" title="{{ $transaction->bank_name }}">
                                        {{ $transaction->bank_name }}
                                    </small>
                                @endif
                            </div>
                        </td>

                        <!-- Reference / Cheque No -->
                        <td class="font-monospace small">
                            @if($transaction->cheque_no)
                                <span class="badge bg-light text-dark border font-monospace" style="font-size: 10.5px;">
                                    CHQ: {{ $transaction->cheque_no }}
                                </span>
                            @elseif($transaction->reference_no)
                                <span class="badge bg-light text-secondary border font-monospace" style="font-size: 10.5px;">
                                    {{ $transaction->reference_no }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <!-- Flow Type Badge -->
                        <td class="text-center">
                            @if($transaction->type === 'income')
                                <span class="badge bg-success-subtle text-success border border-success" style="font-size: 10.5px;">
                                    <i class="bi bi-arrow-down-left me-0.5"></i> Deposit
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 10.5px;">
                                    <i class="bi bi-arrow-up-right me-0.5"></i> Withdrawal
                                </span>
                            @endif
                        </td>

                        <!-- Inflow Amount -->
                        <td class="text-end font-monospace">
                            @if($transaction->type === 'income')
                                <span class="badge-inflow">
                                    + {{ number_format($transaction->amount, 2) }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <!-- Outflow Amount -->
                        <td class="text-end font-monospace">
                            @if($transaction->type === 'expense')
                                <span class="badge-outflow">
                                    - {{ number_format($transaction->amount, 2) }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="text-center no-print">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border px-2 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 11px;">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="font-size: 12.5px; border-radius: 8px;">
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('bank-transactions.show', $transaction->id) }}">
                                            <i class="bi bi-eye text-primary me-2"></i> View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('bank-transactions.edit', $transaction->id) }}">
                                            <i class="bi bi-pencil text-secondary me-2"></i> Edit Record
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6 class="fw-bold text-dark mb-1">No Transactions Found</h6>
                                <p class="text-muted small mb-3">No records matched the selected filters. Try broadening your date or channel criteria.</p>
                                <a href="{{ route('bank-transactions.create') }}" class="btn btn-primary btn-sm shadow-sm" target="_blank">
                                    <i class="bi bi-plus-lg me-1"></i> Record New Transaction
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <!-- Statement Period Totals in Footer -->
            <tfoot>
                <tr>
                    <td colspan="5" class="text-uppercase text-muted" style="letter-spacing: 0.5px; font-size: 12px;">
                        <strong>Period Totals (All Matching Records)</strong>
                    </td>
                    <td class="text-end font-monospace text-success fw-bold" style="font-size: 13.5px;">
                        + Rs. {{ number_format($periodIncomeTotal, 2) }}
                    </td>
                    <td class="text-end font-monospace text-danger fw-bold" style="font-size: 13.5px;">
                        - Rs. {{ number_format($periodExpenseTotal, 2) }}
                    </td>
                    <td class="no-print"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Pagination & Summary Footer -->
    <div class="card-footer bg-white py-3 px-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="text-muted small">
            Showing <span class="fw-semibold text-dark">{{ $transactions->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $transactions->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $transactions->total() }}</span> entries
        </div>
        <div>
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<!-- Print Only Signatures -->
<div class="print-signatures">
    <div>
        <div style="border-top: 1px solid #000; width: 220px; padding-top: 5px; text-align: center;">
            <strong>Finance Officer / Prepared By</strong>
            <div class="text-muted small">Signature & Date</div>
        </div>
    </div>
    <div>
        <div style="border-top: 1px solid #000; width: 220px; padding-top: 5px; text-align: center;">
            <strong>Internal Auditor / Verified By</strong>
            <div class="text-muted small">Signature & Date</div>
        </div>
    </div>
    <div>
        <div style="border-top: 1px solid #000; width: 220px; padding-top: 5px; text-align: center;">
            <strong>Managing Director / Approved By</strong>
            <div class="text-muted small">Signature & Date</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function applyPreset(preset) {
        const today = new Date();
        const formatDate = (date) => {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        };

        let fromDate, toDate;

        if (preset === 'today') {
            fromDate = formatDate(today);
            toDate = formatDate(today);
        } else if (preset === 'this_week') {
            const firstDay = new Date(today.setDate(today.getDate() - today.getDay() + (today.getDay() === 0 ? -6 : 1)));
            const lastDay = new Date(firstDay);
            lastDay.setDate(lastDay.getDate() + 6);
            fromDate = formatDate(firstDay);
            toDate = formatDate(lastDay);
        } else if (preset === 'this_month') {
            const first = new Date(today.getFullYear(), today.getMonth(), 1);
            const last = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            fromDate = formatDate(first);
            toDate = formatDate(last);
        } else if (preset === 'last_month') {
            const first = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const last = new Date(today.getFullYear(), today.getMonth(), 0);
            fromDate = formatDate(first);
            toDate = formatDate(last);
        } else if (preset === 'this_year') {
            const first = new Date(today.getFullYear(), 0, 1);
            const last = new Date(today.getFullYear(), 11, 31);
            fromDate = formatDate(first);
            toDate = formatDate(last);
        }

        if (fromDate && toDate) {
            document.getElementById('date_from').value = fromDate;
            document.getElementById('date_to').value = toDate;
            document.getElementById('txnReportFilterForm').submit();
        }
    }
</script>
@endsection
