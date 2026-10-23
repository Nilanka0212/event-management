@extends('layouts.app')
@section('title', 'Cheques Report')
@section('page-title', 'Cheques Report')

@section('content')
<style>
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
            <h3 class="fw-bold mb-0">COMPANY CHEQUES AUDIT &amp; CLEARANCE REPORT</h3>
            <p class="text-muted mb-0 small">Official ledger statement of inward cheques, outward disbursements, bank clearing, and returns</p>
        </div>
        <div class="text-end">
            <div class="fw-bold">Statement Period:</div>
            <div class="small">{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</div>
            <div class="text-muted small">Generated on: {{ now()->format('d M Y, h:i A') }}</div>
        </div>
    </div>
</div>

<!-- Page Header (EventPro / Tritcal Style) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 no-print">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
            style="width: 44px; height: 44px; background: linear-gradient(135deg, #484646 0%, #3a3838 100%); font-size: 20px;">
            <i class="bi bi-file-earmark-check"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">Cheques Report</h4>
                <span class="badge bg-light text-secondary border px-2 py-0.5" style="font-size: 11px; font-weight: 700;">
                    Audited Clearance Ledger
                </span>
                <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 11px;">
                    {{ $cheques->total() }} Records
                </span>
            </div>
            <p class="text-muted small mb-0">Detailed audit log of received &amp; issued cheques, bank clearance milestones, and return history.</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap page-header-actions">
        <button type="button" class="btn btn-outline-secondary shadow-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print Report
        </button>
        <a href="{{ route('cheques.index') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-card-heading me-1"></i> Cheques Management
        </a>
    </div>
</div>

<!-- ============================================================== -->
<!-- 1. KPI STAT CARDS OVERVIEW (EventPro Style)                   -->
<!-- ============================================================== -->
<div class="row g-3 mb-4">
    <!-- 1. Total Inward Cheques -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
            <div class="card-body p-3.5 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 44px; height: 44px; background-color: #ecfdf5; color: #059669; font-size: 20px;">
                    <i class="bi bi-arrow-down-left-circle-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Cheques In</span>
                    <h5 class="fw-bold text-success mb-0 mt-0.5 text-truncate" style="font-size: 17px;">
                        Rs. {{ number_format($inTotal, 2) }}
                    </h5>
                    <small class="text-muted" style="font-size: 11px;">Received collections</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Total Outward Cheques -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
            <div class="card-body p-3.5 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 44px; height: 44px; background-color: #f1f5f9; color: #475569; font-size: 20px;">
                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Cheques Out</span>
                    <h5 class="fw-bold text-dark mb-0 mt-0.5 text-truncate" style="font-size: 17px;">
                        Rs. {{ number_format($outTotal, 2) }}
                    </h5>
                    <small class="text-muted" style="font-size: 11px;">Issued disbursements</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Realized / Cleared -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
            <div class="card-body p-3.5 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 44px; height: 44px; background-color: #eff6ff; color: #2563eb; font-size: 20px;">
                    <i class="bi bi-check2-all"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Realized (In Bank)</span>
                    <h5 class="fw-bold text-primary mb-0 mt-0.5 text-truncate" style="font-size: 17px;">
                        Rs. {{ number_format($realizedTotal, 2) }}
                    </h5>
                    <small class="text-muted" style="font-size: 11px;">Officially cleared</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Pending Clearance -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
            <div class="card-body p-3.5 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 44px; height: 44px; background-color: #fffbeb; color: #d97706; font-size: 20px;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Pending Clearing</span>
                    <h5 class="fw-bold text-warning mb-0 mt-0.5 text-truncate" style="font-size: 17px;">
                        Rs. {{ number_format($pendingTotal, 2) }}
                    </h5>
                    <small class="text-muted" style="font-size: 11px;">In hand &amp; clearing</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Returned Cheques -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
            <div class="card-body p-3.5 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 44px; height: 44px; background-color: #fff1f2; color: #e11d48; font-size: 20px;">
                    <i class="bi bi-arrow-return-left"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Returned</span>
                    <h5 class="fw-bold text-danger mb-0 mt-0.5 text-truncate" style="font-size: 17px;">
                        Rs. {{ number_format($returnedTotal, 2) }}
                    </h5>
                    <small class="text-danger fw-semibold" style="font-size: 11px;">Bounced / Cancelled</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 2. QUICK PERIODS & ADVANCED SEARCH & FILTER CARD              -->
<!-- ============================================================== -->
<div class="card shadow-sm border-0 mb-4 no-print" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
            <i class="bi bi-funnel text-secondary"></i> Search &amp; Report Filters
        </h6>
        @if(request()->anyFilled(['search', 'type', 'status', 'bank_id', 'date_from', 'date_to']))
            <a href="{{ route('reports.cheques') }}" class="btn btn-sm btn-link text-decoration-none text-danger p-0" style="font-size: 12px;">
                <i class="bi bi-x-circle me-1"></i> Clear Filters
            </a>
        @endif
    </div>

    <div class="card-body p-3 p-md-4">
        <!-- Quick Period Presets -->
        <div class="d-flex align-items-center gap-2 flex-wrap mb-3 pb-3 border-bottom">
            <span class="small fw-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Quick Ranges:</span>
            <a href="{{ route('reports.cheques', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => now()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')])) }}"
               class="btn btn-sm {{ $dateFrom === now()->format('Y-m-d') && $dateTo === now()->format('Y-m-d') ? 'btn-primary' : 'btn-outline-secondary' }}"
               style="font-size: 11.5px; border-radius: 6px; padding: 3px 10px;">Today</a>
            <a href="{{ route('reports.cheques', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => now()->startOfWeek()->format('Y-m-d'), 'date_to' => now()->endOfWeek()->format('Y-m-d')])) }}"
               class="btn btn-sm btn-outline-secondary"
               style="font-size: 11.5px; border-radius: 6px; padding: 3px 10px;">This Week</a>
            <a href="{{ route('reports.cheques', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => now()->startOfMonth()->format('Y-m-d'), 'date_to' => now()->endOfMonth()->format('Y-m-d')])) }}"
               class="btn btn-sm {{ $dateFrom === now()->startOfMonth()->format('Y-m-d') ? 'btn-primary' : 'btn-outline-secondary' }}"
               style="font-size: 11.5px; border-radius: 6px; padding: 3px 10px;">This Month</a>
            <a href="{{ route('reports.cheques', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'date_to' => now()->subMonth()->endOfMonth()->format('Y-m-d')])) }}"
               class="btn btn-sm btn-outline-secondary"
               style="font-size: 11.5px; border-radius: 6px; padding: 3px 10px;">Last Month</a>
            <a href="{{ route('reports.cheques', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => now()->startOfYear()->format('Y-m-d'), 'date_to' => now()->endOfYear()->format('Y-m-d')])) }}"
               class="btn btn-sm btn-outline-secondary"
               style="font-size: 11.5px; border-radius: 6px; padding: 3px 10px;">This Year</a>
        </div>

        <form method="GET" action="{{ route('reports.cheques') }}" id="chequesReportFilterForm">
            <div class="row g-3">
                <!-- 1. Quick Search -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-search me-1"></i> Quick Search
                    </label>
                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cheque #, party name, notes...">
                </div>

                <!-- 2. Type Filter -->
                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-arrow-left-right me-1"></i> Cheque Type
                    </label>
                    <select name="type" class="form-select">
                        <option value="all" @selected($type === 'all')>All Cheques</option>
                        <option value="IN" @selected($type === 'IN')>Cheque In (Received)</option>
                        <option value="OUT" @selected($type === 'OUT')>Cheque Out (Issued)</option>
                    </select>
                </div>

                <!-- 3. Status Filter -->
                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-tag me-1"></i> Status
                    </label>
                    <select name="status" class="form-select">
                        <option value="all" @selected($status === 'all')>All Statuses</option>
                        <option value="In Hand" @selected($status === 'In Hand')>In Hand</option>
                        <option value="Deposited" @selected($status === 'Deposited')>Deposited</option>
                        <option value="Realized" @selected($status === 'Realized')>Realized (Cleared)</option>
                        <option value="Returned" @selected($status === 'Returned')>Returned (Bounced)</option>
                        <option value="Issued" @selected($status === 'Issued')>Issued</option>
                    </select>
                </div>

                <!-- 4. Company Bank -->
                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-bank2 me-1"></i> Company Bank
                    </label>
                    <select name="bank_id" class="form-select">
                        <option value="">All Accounts</option>
                        @foreach($banks as $b)
                            <option value="{{ $b->id }}" @selected($bankId == $b->id)>{{ $b->bank_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 5. Date Range -->
                <div class="col-12 col-sm-6 col-md-3 col-lg-1.5" style="flex: 1 1 auto; max-width: 170px;">
                    <label class="form-label small fw-bold text-muted mb-1">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>

                <div class="col-12 col-sm-6 col-md-3 col-lg-1.5" style="flex: 1 1 auto; max-width: 220px;">
                    <label class="form-label small fw-bold text-muted mb-1">To Date</label>
                    <div class="d-flex gap-2">
                        <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                        <button type="submit" class="btn btn-primary shadow-sm" style="min-width: 42px;" title="Apply Filter">
                            <i class="bi bi-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 3. REPORT DATA TABLE CARD (EventPro Unified Styling)           -->
<!-- ============================================================== -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #edf2f7 !important;">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
            <i class="bi bi-table text-secondary"></i>
            Cheques Clearance Audit Ledger
            <span class="text-muted fw-normal" style="font-size: 12px;">({{ $cheques->total() }} records | {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }})</span>
        </h6>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-report" style="font-size: 13px;">
            <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <tr>
                    <th class="text-uppercase fw-bold text-secondary" style="font-size: 11px; letter-spacing: 0.5px; padding: 12px 16px; width: 130px;">Cheque #</th>
                    <th class="text-uppercase fw-bold text-secondary" style="font-size: 11px; letter-spacing: 0.5px; padding: 12px 16px; width: 90px;">Type</th>
                    <th class="text-uppercase fw-bold text-secondary" style="font-size: 11px; letter-spacing: 0.5px; padding: 12px 16px;">Party (Customer / Payee)</th>
                    <th class="text-uppercase fw-bold text-secondary" style="font-size: 11px; letter-spacing: 0.5px; padding: 12px 16px;">Bank Details</th>
                    <th class="text-uppercase fw-bold text-secondary" style="font-size: 11px; letter-spacing: 0.5px; padding: 12px 16px; width: 110px;">Cheque Date</th>
                    <th class="text-uppercase fw-bold text-secondary" style="font-size: 11px; letter-spacing: 0.5px; padding: 12px 16px; width: 120px;">Clearance Date</th>
                    <th class="text-uppercase fw-bold text-secondary text-end" style="font-size: 11px; letter-spacing: 0.5px; padding: 12px 16px; width: 140px;">Amount</th>
                    <th class="text-uppercase fw-bold text-secondary text-center" style="font-size: 11px; letter-spacing: 0.5px; padding: 12px 16px; width: 120px;">Status</th>
                    <th class="text-uppercase fw-bold text-secondary" style="font-size: 11px; letter-spacing: 0.5px; padding: 12px 16px;">Remarks / Reason</th>
                </tr>
            </thead>
            <tbody>
                @php $sumPage = 0; @endphp
                @forelse($cheques as $c)
                    @php $sumPage += (float) $c->amount; @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <!-- Cheque # -->
                        <td style="padding: 12px 16px;">
                            <strong class="font-monospace text-dark" style="font-size: 13.5px;">#{{ $c->cheque_number }}</strong>
                        </td>

                        <!-- Type -->
                        <td style="padding: 12px 16px;">
                            @if($c->type === 'IN')
                                <span class="badge" style="background:#ecfdf5; color:#047857; border:1px solid #a7f3d0; font-size:11px; font-weight:700; padding:4px 7px;">
                                    <i class="bi bi-arrow-down-left me-0.5"></i> IN
                                </span>
                            @else
                                <span class="badge" style="background:#fff1f2; color:#be123c; border:1px solid #fecdd3; font-size:11px; font-weight:700; padding:4px 7px;">
                                    <i class="bi bi-arrow-up-right me-0.5"></i> OUT
                                </span>
                            @endif
                        </td>

                        <!-- Party -->
                        <td style="padding: 12px 16px;">
                            @if($c->type === 'IN')
                                <div class="fw-bold text-dark">{{ $c->customer?->name ?? 'General Customer' }}</div>
                                @if($c->order)
                                    <small class="text-muted d-block mt-0.5" style="font-size: 11px;">Order: {{ $c->order->order_no }}</small>
                                @endif
                            @else
                                <div class="fw-bold text-dark">{{ $c->payee_name }}</div>
                                @if($c->expense)
                                    <small class="text-muted d-block mt-0.5" style="font-size: 11px;">Exp: {{ $c->expense->expense_no }}</small>
                                @endif
                            @endif
                        </td>

                        <!-- Bank Details -->
                        <td style="padding: 12px 16px;">
                            @if($c->type === 'IN')
                                <div class="small text-secondary fw-semibold">{{ $c->payer_bank ?: 'Customer Bank' }}</div>
                                @if($c->bank)
                                    <small class="text-primary fw-semibold d-block mt-0.5" style="font-size: 11px;">
                                        <i class="bi bi-building me-1"></i>Banked in {{ $c->bank->bank_name }}
                                    </small>
                                @endif
                            @else
                                <div class="small fw-semibold text-primary">{{ $c->bank?->bank_name ?: 'Company Bank' }}</div>
                                <small class="text-muted d-block" style="font-size: 11px;">Acc: {{ $c->bank?->account_number }}</small>
                            @endif
                        </td>

                        <!-- Cheque Date -->
                        <td style="padding: 12px 16px;">
                            <span class="small">{{ $c->cheque_date ? $c->cheque_date->format('d M Y') : '-' }}</span>
                        </td>

                        <!-- Cleared Date -->
                        <td style="padding: 12px 16px;">
                            @if($c->clearance_date)
                                <span class="small text-success fw-bold"><i class="bi bi-check-lg me-1"></i>{{ $c->clearance_date->format('d M Y') }}</span>
                            @elseif($c->return_date)
                                <span class="small text-danger fw-bold"><i class="bi bi-x-lg me-1"></i>{{ $c->return_date->format('d M Y') }}</span>
                            @else
                                <span class="small text-muted">-</span>
                            @endif
                        </td>

                        <!-- Amount -->
                        <td class="text-end" style="padding: 12px 16px;">
                            <strong class="fw-bold {{ $c->status === 'Returned' ? 'text-danger text-decoration-line-through' : 'text-dark' }}" style="font-size: 14px;">
                                Rs. {{ number_format($c->amount, 2) }}
                            </strong>
                        </td>

                        <!-- Status Badge -->
                        <td class="text-center" style="padding: 12px 16px;">
                            @if($c->status === 'In Hand')
                                <span class="badge" style="background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-weight: 700; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                                    In Hand
                                </span>
                            @elseif($c->status === 'Deposited')
                                <span class="badge" style="background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-weight: 700; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                                    Deposited
                                </span>
                            @elseif($c->status === 'Realized')
                                <span class="badge" style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-weight: 700; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                                    Realized
                                </span>
                            @elseif($c->status === 'Returned')
                                <span class="badge" style="background-color: #fff1f2; color: #be123c; border: 1px solid #fecdd3; font-weight: 700; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                                    Returned
                                </span>
                            @elseif($c->status === 'Issued')
                                <span class="badge" style="background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-weight: 700; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                                    Issued
                                </span>
                            @else
                                <span class="badge bg-secondary text-white">{{ $c->status }}</span>
                            @endif
                        </td>

                        <!-- Remarks -->
                        <td style="padding: 12px 16px;">
                            @if($c->status === 'Returned' && $c->return_reason)
                                <span class="text-danger small fw-semibold"><i class="bi bi-exclamation-circle me-1"></i>{{ $c->return_reason }}</span>
                            @else
                                <span class="text-muted small">{{ $c->notes ?: '-' }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary"></i>
                            No cheques found matching the specified period and filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($cheques->count() > 0)
                <tfoot style="background: #f8fafc; border-top: 2px solid #cbd5e1; font-weight: 700;">
                    <tr>
                        <td colspan="6" class="text-end" style="padding: 13px 16px; font-size: 13px;">Page Total:</td>
                        <td class="text-end text-dark" style="padding: 13px 16px; font-size: 15px;">
                            Rs. {{ number_format($sumPage, 2) }}
                        </td>
                        <td colspan="2" style="padding: 13px 16px;"></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    @if($cheques->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap gap-2 no-print">
            <span class="text-muted small">Showing {{ $cheques->firstItem() }} to {{ $cheques->lastItem() }} of {{ $cheques->total() }} records</span>
            {{ $cheques->links() }}
        </div>
    @endif
</div>

<!-- Print Only Signatures -->
<div class="print-signatures">
    <div>
        <p class="mb-5 small text-muted">Prepared By: __________________________</p>
        <p class="small fw-bold">Finance / Accounts Officer</p>
    </div>
    <div class="text-end">
        <p class="mb-5 small text-muted">Certified Correct: __________________________</p>
        <p class="small fw-bold">Managing Director / Partner</p>
    </div>
</div>
@endsection
