@extends('layouts.app')
@section('title', 'Record Transaction')
@section('page-title', 'Record Transaction')

@section('content')
<style>
    /* Compact, Modern Flow Classification Toggle Cards */
    .txn-type-card {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        user-select: none;
    }
    .txn-type-card:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .txn-type-card.active.income {
        border-color: #10b981;
        background: #f0fdf4;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
    }
    .txn-type-card.active.expense {
        border-color: #ef4444;
        background: #fef2f2;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
    }
    .txn-type-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .txn-type-card.active.income .txn-type-icon {
        background: #d1fae5;
        color: #059669;
    }
    .txn-type-card.active.expense .txn-type-icon {
        background: #fee2e2;
        color: #dc2626;
    }

    .amount-input-box {
        font-size: 18px !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px;
        height: 38px;
        transition: all 0.2s ease;
    }
    .amount-input-box.income {
        color: #059669 !important;
        border-color: #a7f3d0 !important;
        background-color: #f0fdf4 !important;
    }
    .amount-input-box.expense {
        color: #dc2626 !important;
        border-color: #fecaca !important;
        background-color: #fef2f2 !important;
    }

    .form-section-divider {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 18px 0 14px;
        padding-bottom: 6px;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<!-- Page Header (Tritcal Style) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
            style="width: 44px; height: 44px; background: linear-gradient(135deg, #484646 0%, #3a3838 100%); font-size: 20px;">
            <i class="bi bi-arrow-left-right"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">Record Transaction</h4>
                <span class="badge bg-light text-secondary border px-2 py-0.5" style="font-size: 11px; font-weight: 600;">
                    Voucher Entry
                </span>
            </div>
            <p class="text-muted small mb-0">Record bank deposits, withdrawals, cheques, internal fund transfers, and ledger adjustments.</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('reports.transactions') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-receipt-cutoff me-1 text-primary"></i> Transactions Report
        </a>
        <a href="{{ route('cash-book') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-journal-text me-1 text-success"></i> Cash Book
        </a>
        <a href="{{ route('bank-book') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-bank me-1 text-primary"></i> Bank Book
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert" style="border-radius: 10px;">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>
            <strong>Success!</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 10px;">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <strong>Please resolve the following errors:</strong>
        </div>
        <ul class="mb-0 small ps-4">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px; border: 1px solid #edf2f7 !important;">
            
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 14px;">
                        <i class="bi bi-pencil-square text-primary"></i> Transaction Entry Form
                    </h6>
                    <small class="text-muted" style="font-size: 11.5px;">Choose transaction flow and complete the financial entry.</small>
                </div>
                <span class="badge bg-light text-dark border px-2.5 py-1" style="font-size: 11px;">
                    <i class="bi bi-calendar3 me-1"></i> {{ date('d M Y') }}
                </span>
            </div>

            <form method="POST" action="{{ route('bank-transactions.store') }}" id="transactionForm">
                @csrf
                <div class="card-body p-4">

                    <!-- ============================================== -->
                    <!-- 1. COMPACT FLOW CLASSIFICATION SELECTOR        -->
                    <!-- ============================================== -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1.5">
                            Transaction Flow <span class="text-danger">*</span>
                        </label>
                        <input type="hidden" name="type" id="transactionTypeInput" value="{{ old('type', 'income') }}">

                        <div class="row g-2.5">
                            <!-- Option 1: Deposit / Income -->
                            <div class="col-12 col-sm-6">
                                <div class="txn-type-card income {{ old('type', 'income') === 'income' ? 'active' : '' }}" 
                                     id="cardTypeIncome" onclick="switchTxnType('income')">
                                    <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                        <div class="txn-type-icon">
                                            <i class="bi bi-arrow-down-left-circle-fill"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 13px;">Deposit / Inflow (Money In)</div>
                                            <small class="text-muted d-block text-truncate" style="font-size: 11px;">Direct deposit, capital, interest</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success flex-shrink-0" style="font-size: 10px;">CREDIT</span>
                                </div>
                            </div>

                            <!-- Option 2: Withdrawal / Expense -->
                            <div class="col-12 col-sm-6">
                                <div class="txn-type-card expense {{ old('type') === 'expense' ? 'active' : '' }}" 
                                     id="cardTypeExpense" onclick="switchTxnType('expense')">
                                    <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                        <div class="txn-type-icon">
                                            <i class="bi bi-arrow-up-right-circle-fill"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 13px;">Withdrawal / Outflow (Money Out)</div>
                                            <small class="text-muted d-block text-truncate" style="font-size: 11px;">Drawings, loan payout, bank charges</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-danger-subtle text-danger border border-danger flex-shrink-0" style="font-size: 10px;">DEBIT</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- 2. PAYMENT CHANNEL, DATE & AMOUNT (SAME ROW)   -->
                    <!-- ============================================== -->
                    <div class="form-section-divider">
                        <i class="bi bi-credit-card-2-front text-primary"></i> Payment Instrument & Amount
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Method -->
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Payment Method <span class="text-danger">*</span>
                            </label>
                            <select name="transaction_method" id="txnMethodSelect" class="form-select fw-semibold" required onchange="onTxnMethodChange(this.value)" style="height: 38px;">
                                <option value="cash" {{ old('transaction_method', 'cash') === 'cash' ? 'selected' : '' }}>💵 Cash Payment</option>
                                <option value="bank_transfer" {{ old('transaction_method') === 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                                <option value="cheque" {{ old('transaction_method') === 'cheque' ? 'selected' : '' }}>📑 Cheque Payment</option>
                            </select>
                        </div>

                        <!-- Date -->
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Transaction Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', $today) }}" required style="height: 38px;">
                        </div>

                        <!-- Amount -->
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Amount to Record (Rs.) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.01" name="amount" id="amountInput" 
                                   class="form-control amount-input-box {{ old('type', 'income') === 'income' ? 'income' : 'expense' }}" 
                                   value="{{ old('amount') }}" placeholder="0.00" required>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- 3. DYNAMIC BANK & CHEQUE DETAILS (WHEN NEEDED) -->
                    <!-- ============================================== -->
                    <div class="row g-3 mb-3 d-none" id="bankDetailsRow">
                        <!-- Bank Account -->
                        <div class="col-12 col-md-6 col-lg-6" id="colBankSelect">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <span id="bankLabelText">Company Bank Account</span> <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-2">
                                <select name="bank_name" id="txnBankSelect" class="form-select" style="height: 38px;">
                                    <option value="">-- Choose Company Bank Account --</option>
                                    @foreach($banks as $b)
                                        <option value="{{ $b->bank_name }} - {{ $b->account_number }} ({{ $b->branch }})"
                                            @selected(old('bank_name') == "{$b->bank_name} - {$b->account_number} ({$b->branch})")>
                                            {{ $b->bank_name }} &bull; {{ $b->account_number }} ({{ $b->branch ?: 'Main' }})
                                        </option>
                                    @endforeach
                                </select>
                                <a href="{{ route('banks.create') }}" target="_blank" class="btn btn-outline-secondary d-flex align-items-center text-nowrap" style="font-size: 12px; height: 38px;">
                                    <i class="bi bi-plus-lg me-1"></i> Add Bank
                                </a>
                            </div>
                        </div>

                        <!-- Cheque Number -->
                        <div class="col-12 col-md-6 d-none" id="colChequeInput">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Cheque Leaf Serial No <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="cheque_no" id="txnChequeInput" class="form-control font-monospace" 
                                   value="{{ old('cheque_no') }}" placeholder="e.g. CHQ-901824" style="height: 38px;">
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- 4. PARTICULARS, REFERENCE & REMARKS            -->
                    <!-- ============================================== -->
                    <div class="form-section-divider">
                        <i class="bi bi-file-text text-primary"></i> Particulars & Audit References
                    </div>

                    <div class="row g-3">
                        <!-- Description -->
                        <div class="col-12 col-md-8">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Description / Particulars <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="description" class="form-control" 
                                value="{{ old('description') }}" placeholder="e.g. Direct cash deposit by client, Monthly bank maintenance fee, Owner drawing..." required style="height: 38px;">
                            <small class="text-muted d-block mt-1" style="font-size: 11px;">Clear particulars explaining the nature of this financial entry.</small>
                        </div>

                        <!-- Reference / Voucher No -->
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Reference / Slip / Voucher No
                            </label>
                            <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no') }}" placeholder="e.g. SLIP-1029, TXN-8812" style="height: 38px;">
                            <small class="text-muted d-block mt-1" style="font-size: 11px;">Optional external bank slip or internal voucher number.</small>
                        </div>

                        <!-- Internal Notes / Remarks -->
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Internal Notes / Remarks
                            </label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Any internal ledger comments, payee contact notes, or reconciliation details...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                </div>

                <!-- Footer Action Buttons -->
                <div class="card-footer bg-light py-3 px-4 border-top d-flex align-items-center justify-content-between">
                    <button type="reset" class="btn btn-outline-secondary shadow-sm" onclick="resetTxnForm()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" id="btnSubmitTxn">
                        <i class="bi bi-check2-circle me-1.5"></i> Save Transaction Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function switchTxnType(type) {
        document.getElementById('transactionTypeInput').value = type;

        const cardIncome = document.getElementById('cardTypeIncome');
        const cardExpense = document.getElementById('cardTypeExpense');
        const amountBox = document.getElementById('amountInput');

        if (type === 'income') {
            cardIncome.classList.add('active');
            cardExpense.classList.remove('active');
            amountBox.classList.add('income');
            amountBox.classList.remove('expense');
        } else {
            cardExpense.classList.add('active');
            cardIncome.classList.remove('active');
            amountBox.classList.add('expense');
            amountBox.classList.remove('income');
        }
    }

    function onTxnMethodChange(method) {
        const row = document.getElementById('bankDetailsRow');
        const colBank = document.getElementById('colBankSelect');
        const colCheque = document.getElementById('colChequeInput');
        const bankSelect = document.getElementById('txnBankSelect');
        const chequeInput = document.getElementById('txnChequeInput');
        const bankLabel = document.getElementById('bankLabelText');

        if (method === 'cash') {
            row.classList.add('d-none');
            colCheque.classList.add('d-none');
            bankSelect.required = false;
            chequeInput.required = false;
            bankSelect.value = '';
            chequeInput.value = '';
        } else if (method === 'bank_transfer') {
            row.classList.remove('d-none');
            colBank.className = 'col-12 col-md-6 col-lg-6';
            colCheque.classList.add('d-none');
            bankLabel.innerText = 'Credited / Debited Bank Account';
            bankSelect.required = true;
            chequeInput.required = false;
            chequeInput.value = '';
        } else if (method === 'cheque') {
            row.classList.remove('d-none');
            colBank.className = 'col-12 col-md-6';
            colCheque.classList.remove('d-none');
            bankLabel.innerText = 'Associated Bank Account';
            bankSelect.required = true;
            chequeInput.required = true;
        }
    }

    function resetTxnForm() {
        setTimeout(() => {
            switchTxnType('income');
            onTxnMethodChange('cash');
        }, 50);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const initialType = document.getElementById('transactionTypeInput').value || 'income';
        switchTxnType(initialType);
        onTxnMethodChange(document.getElementById('txnMethodSelect').value);
    });
</script>
@endsection