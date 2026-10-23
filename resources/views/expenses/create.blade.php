@extends('layouts.app')
@section('title', 'Record Expense')
@section('page-title', 'Record Expense')

@section('content')
<style>
    .expense-type-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
        position: relative;
    }
    .expense-type-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .expense-type-card.active {
        border-color: #484646;
        background: #f8fafc;
        box-shadow: 0 4px 14px rgba(72, 70, 70, 0.12);
    }
    .expense-type-card.active .type-icon {
        background: #484646;
        color: #ffffff;
    }
    .type-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #484646;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }

    /* Customer Order Card (Direct Expense) */
    .order-select-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
        cursor: pointer;
        transition: all 0.15s ease;
        background: #ffffff;
    }
    .order-select-card:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .order-select-card.selected {
        border-color: #16a34a;
        background: #f0fdf4;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.15);
    }

    .form-section-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 16px;
    }
    .form-section-badge {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #484646;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .amount-input-box {
        font-size: 20px !important;
        font-weight: 700 !important;
        color: #dc2626 !important;
        letter-spacing: -0.3px;
    }
    #normalCategoryWrapper .select2-container,
    #sectionNormalExpense .select2-container {
        width: 100% !important;
    }
</style>

<!-- Page Header (Tritcal Style) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
            style="width: 44px; height: 44px; background: linear-gradient(135deg, #484646 0%, #3a3838 100%); font-size: 20px;">
            <i class="bi bi-wallet2"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">Record Expense</h4>
                <span class="badge bg-light text-secondary border px-2 py-0.5" style="font-size: 11px; font-weight: 600;">
                    Voucher Entry
                </span>
            </div>
            <p class="text-muted small mb-0">Record direct event-related expenditures or general company operational expenses.</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('reports.expenses') }}" class="btn btn-outline-secondary shadow-sm" target="_blank">
            <i class="bi bi-receipt-cutoff me-1 text-primary"></i> View Expenses Report
        </a>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary shadow-sm" target="_blank">
            <i class="bi bi-box-seam me-1"></i> Orders Ledger
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

@if($errors->any())
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

<!-- Main Form Container -->
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px; border: 1px solid #edf2f7 !important;">
            
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 14px;">
                        <i class="bi bi-pencil-square text-primary"></i> Expense Entry Terminal
                    </h6>
                    <small class="text-muted" style="font-size: 11.5px;">Choose the expense type and fill in the payment details.</small>
                </div>
                <span class="badge bg-light text-dark border px-2.5 py-1" style="font-size: 11px;">
                    <i class="bi bi-calendar3 me-1"></i> {{ date('d M Y') }}
                </span>
            </div>

            <form method="POST" action="{{ route('expenses.store') }}" id="expenseForm">
                @csrf
                <div class="card-body p-4">

                    <!-- ============================================== -->
                    <!-- EXPENSE TYPE SELECTOR: DIRECT VS NORMAL        -->
                    <!-- ============================================== -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark mb-2">
                            Select Expense Classification <span class="text-danger">*</span>
                        </label>
                        <input type="hidden" name="type" id="expenseTypeInput" value="{{ old('type', 'direct') }}">

                        <div class="row g-3">
                            <!-- Option 1: Direct Expense -->
                            <div class="col-12 col-md-6">
                                <div class="expense-type-card {{ old('type', 'direct') === 'direct' ? 'active' : '' }}" 
                                     id="cardTypeDirect" onclick="switchExpenseType('direct')">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="type-icon flex-shrink-0">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark" style="font-size: 13.5px;">Direct Event Expense</h6>
                                            <p class="text-muted small mb-0" style="font-size: 11.5px; line-height: 1.4;">
                                                Incurred directly for a customer event or order (e.g. Venue, Catering, Sounds, Decor).
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Option 2: Normal Expense -->
                            <div class="col-12 col-md-6">
                                <div class="expense-type-card {{ old('type') === 'normal' ? 'active' : '' }}" 
                                     id="cardTypeNormal" onclick="switchExpenseType('normal')">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="type-icon flex-shrink-0">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark" style="font-size: 13.5px;">Normal Operational Expense</h6>
                                            <p class="text-muted small mb-0" style="font-size: 11.5px; line-height: 1.4;">
                                                General business overheads (e.g. Office Rent, Electricity, Staff, Fuel, Stationery).
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- SECTION: DIRECT EXPENSE (Customer & Orders)    -->
                    <!-- ============================================== -->
                    <div id="sectionDirectExpense" class="{{ old('type', 'direct') === 'direct' ? '' : 'd-none' }}">
                        <div class="form-section-title">
                            <span class="form-section-badge">1</span>
                            <span>Customer & Related Order Information</span>
                        </div>

                        <!-- Customer Select -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Customer <span class="text-danger">*</span>
                            </label>
                            <select id="directCustomerId" class="form-select searchable-select" onchange="onDirectCustomerChange(this.value)">
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ (old('customer_id', $preselectedCustomerId) == $c->id) ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->phone ?: 'No phone' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                <i class="bi bi-info-circle me-1"></i> Select customer to inspect their active orders.
                            </small>
                        </div>

                        <!-- Customer Orders Container (Appears in front) -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Select Target Order / Event <span class="text-danger">*</span>
                            </label>

                            <!-- Placeholder when no customer selected -->
                            <div id="ordersEmptyPlaceholder" class="p-3 text-center border rounded-3 bg-light text-muted small" style="border-style: dashed !important;">
                                <i class="bi bi-arrow-up-circle me-1"></i> Please select a customer above to view and link their orders.
                            </div>

                            <!-- Dynamic Orders Grid -->
                            <div id="customerOrdersList" class="d-none">
                                <div class="row g-2" id="ordersGridCards">
                                    <!-- Populated via JavaScript -->
                                </div>
                            </div>

                            <!-- Hidden inputs for selected order and event -->
                            <input type="hidden" name="order_id" id="selectedOrderId" value="{{ old('order_id', $preselectedOrderId) }}">
                            <input type="hidden" name="event_id" id="selectedEventId" value="{{ old('event_id') }}">
                        </div>

                    </div>

                    <!-- ============================================== -->
                    <!-- SECTION: EXPENSE CATEGORY & AMOUNT ROW         -->
                    <!-- ============================================== -->
                    <div class="row g-3 mb-3 align-items-start">
                        <!-- Category Selector (Direct or Normal) -->
                        <div class="col-12 col-md-6">
                            <!-- Direct Expense Category (visible when Direct) -->
                            <div id="directCategoryWrapper" class="{{ old('type', 'direct') === 'direct' ? '' : 'd-none' }}">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    Direct Expense Category / Type <span class="text-danger">*</span>
                                </label>
                                <select name="direct_category" id="directCategorySelect" class="form-select" onchange="syncDirectCategory(this.value)">
                                    <option value="Venue & Hall Hire">Venue & Hall Hire</option>
                                    <option value="Catering & Food Service">Catering & Food Service</option>
                                    <option value="Decorations & Florist">Decorations & Florist</option>
                                    <option value="Sounds, DJ & Lighting">Sounds, DJ & Lighting</option>
                                    <option value="Photography & Videography">Photography & Videography</option>
                                    <option value="Vehicle & Transport Logistics">Vehicle & Transport Logistics</option>
                                    <option value="Equipment & Prop Rental">Equipment & Prop Rental</option>
                                    <option value="Event Crew & Daily Wages">Event Crew & Daily Wages</option>
                                    <option value="Cake & Confectionery">Cake & Confectionery</option>
                                    <option value="Entertainment & Artists">Entertainment & Artists</option>
                                    <option value="Other Direct Cost">Other Direct Cost</option>
                                </select>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Categorizes the direct event cost in profit & loss accounts.</small>
                            </div>

                            <!-- Normal Operational Expense Category (visible when Normal) -->
                            <div id="normalCategoryWrapper" class="{{ old('type') === 'normal' ? '' : 'd-none' }}">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    Expense Category <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="flex: 1 1 auto; min-width: 0;">
                                        <select name="normal_category" id="normalCategorySelect" class="form-select" onchange="syncNormalCategory(this.value)">
                                            <option value="">-- Choose Category --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->name }}" {{ old('category') == $cat->name ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-primary d-flex align-items-center gap-1.5 px-3 flex-shrink-0 text-nowrap text-white" 
                                            data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Create New Category" style="height: 38px; border-radius: 8px;">
                                        <i class="bi bi-plus-lg text-white"></i>
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                    Click <strong>+</strong> to add a custom category via popup without leaving this page.
                                </small>
                            </div>
                        </div>

                        <!-- Amount to Pay (Rs.) * (Right beside Expense Category) -->
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-dark mb-1">
                                Amount to Pay (Rs.) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0.01" name="amount" class="form-control amount-input-box" 
                                    value="{{ old('amount') }}" placeholder="0.00" required style="height: 38px;">
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 11px;">Total disbursed voucher value.</small>
                        </div>
                    </div>

                    <!-- Hidden Unified Category Field submitted to server -->
                    <input type="hidden" name="category" id="unifiedCategoryInput" value="{{ old('category', 'Venue & Hall Hire') }}">

                    <!-- ============================================== -->
                    <!-- SECTION 2: PAYMENT & TRANSACTION DETAILS       -->
                    <!-- ============================================== -->
                    <div class="mt-4">
                        <div class="form-section-title">
                            <span class="form-section-badge">2</span>
                            <span>Payment & Transaction Details</span>
                        </div>

                        <div class="row g-3">
                            <!-- Description -->
                            <div class="col-12 col-md-8">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    Expense Description / Payee <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="description" class="form-control" 
                                    value="{{ old('description') }}" placeholder="e.g. Advance paid to Grand Monarch Hall, or Diesel for logistics van..." required>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Clear description of what this payment was for.</small>
                            </div>

                            <!-- Expense Date -->
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    Expense Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $today) }}" required>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Date when expense was incurred.</small>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                <select name="payment_method" id="expensePaymentMethod" class="form-select fw-semibold" required onchange="onExpenseMethodChange(this.value)">
                                    <option value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}>💵 Cash Payment</option>
                                    <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer / Deposit</option>
                                    <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>📑 Cheque Payment</option>
                                </select>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Payment source for ledger posting.</small>
                            </div>

                            <!-- Dynamic Bank Account -->
                            <div class="col-12 col-md-8 d-none" id="expenseBankCol">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    <span id="bankLabelText">Company Bank Account</span> <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex gap-2">
                                    <select name="bank_name" id="expenseBankSelect" class="form-select">
                                        <option value="">-- Choose Debited Bank Account --</option>
                                        @foreach($banks as $b)
                                            <option value="{{ $b->bank_name }} - {{ $b->account_number }} ({{ $b->branch }})"
                                                @selected(old('bank_name') == "{$b->bank_name} - {$b->account_number} ({$b->branch})")>
                                                {{ $b->bank_name }} &bull; {{ $b->account_number }} ({{ $b->branch ?: 'Main' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <a href="{{ route('banks.create') }}" target="_blank" class="btn btn-outline-secondary d-flex align-items-center text-nowrap" style="font-size: 12px;">
                                        <i class="bi bi-plus-lg me-1"></i> Add Bank
                                    </a>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Account from which funds were withdrawn.</small>
                            </div>

                            <!-- Dynamic Cheque Number -->
                            <div class="col-12 col-md-4 d-none" id="expenseChequeCol">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    Cheque Serial Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="cheque_no" id="expenseChequeInput" class="form-control font-monospace" 
                                    value="{{ old('cheque_no') }}" placeholder="e.g. CHQ-901824">
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Cheque serial leaf number.</small>
                            </div>

                            <!-- Reference / Voucher No -->
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    Reference / Voucher / Bill No
                                </label>
                                <input type="text" name="reference" class="form-control" value="{{ old('reference') }}" placeholder="e.g. INV-8819 or Slip #">
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Optional vendor invoice or slip number.</small>
                            </div>

                            <!-- Notes / Remarks -->
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    Internal Notes / Remarks
                                </label>
                                <textarea name="notes" rows="2" class="form-control" placeholder="Any internal notes or vendor details...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Action Buttons -->
                <div class="card-footer bg-light py-3 px-4 border-top d-flex align-items-center justify-content-between">
                    <button type="reset" class="btn btn-outline-secondary shadow-sm" onclick="resetExpenseForm()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary shadow-sm px-4 py-2 fw-semibold">
                        <i class="bi bi-check2-circle me-1.5"></i> Confirm & Record Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- POPUP MODAL: ADD NEW EXPENSE CATEGORY          -->
<!-- ============================================== -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-light py-3">
                <h6 class="modal-title fw-bold text-dark mb-0 d-flex align-items-center gap-2" id="addCategoryModalLabel">
                    <i class="bi bi-folder-plus text-primary"></i> Add New Expense Category
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="categoryModalAlert" class="alert alert-danger d-none py-2 px-3 small" style="border-radius: 8px;"></div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark mb-1">
                        Category Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="modalCategoryName" class="form-control" placeholder="e.g. Office Refreshments, Staff Uniforms..." required>
                    <small class="text-muted" style="font-size: 11px;">Unique category name for accounting.</small>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-bold text-dark mb-1">
                        Description (Optional)
                    </label>
                    <textarea id="modalCategoryDesc" class="form-control" rows="2" placeholder="Brief note about what this category covers..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-4">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm fw-semibold" id="btnSaveCategory" onclick="saveCategoryAjax()">
                    <i class="bi bi-check2 me-1"></i> Save Category
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Dataset of all orders with customer and event info
    const allOrders = [
        @foreach($orders as $ord)
        {
            id: "{{ $ord->id }}",
            orderNo: "{{ $ord->order_no }}",
            customerId: "{{ $ord->customer_id }}",
            customerName: "{{ addslashes($ord->customer?->name ?? 'Customer') }}",
            eventId: "{{ $ord->event_id ?? '' }}",
            eventName: "{{ addslashes($ord->event?->event_name ?? 'Event') }}",
            eventDate: "{{ $ord->event?->event_date?->format('M d, Y') ?? 'N/A' }}",
            venue: "{{ addslashes($ord->event?->venue ?? 'No Venue') }}",
            total: {{ (float)$ord->total_amount }},
            paid: {{ (float)$ord->paid_amount }},
            balance: {{ (float)$ord->balance }}
        },
        @endforeach
    ];

    // Switch between Direct Expense and Normal Expense
    function switchExpenseType(type) {
        document.getElementById('expenseTypeInput').value = type;

        const cardDirect = document.getElementById('cardTypeDirect');
        const cardNormal = document.getElementById('cardTypeNormal');
        const sectionDirect = document.getElementById('sectionDirectExpense');
        const directCatWrap = document.getElementById('directCategoryWrapper');
        const normalCatWrap = document.getElementById('normalCategoryWrapper');

        if (type === 'direct') {
            cardDirect.classList.add('active');
            cardNormal.classList.remove('active');
            sectionDirect.classList.remove('d-none');
            if (directCatWrap) directCatWrap.classList.remove('d-none');
            if (normalCatWrap) normalCatWrap.classList.add('d-none');
            syncDirectCategory(document.getElementById('directCategorySelect').value);
        } else {
            cardNormal.classList.add('active');
            cardDirect.classList.remove('active');
            sectionDirect.classList.add('d-none');
            if (directCatWrap) directCatWrap.classList.add('d-none');
            if (normalCatWrap) normalCatWrap.classList.remove('d-none');
            syncNormalCategory(document.getElementById('normalCategorySelect').value);
        }
    }

    function syncDirectCategory(val) {
        if (document.getElementById('expenseTypeInput').value === 'direct') {
            document.getElementById('unifiedCategoryInput').value = val;
        }
    }

    function syncNormalCategory(val) {
        if (document.getElementById('expenseTypeInput').value === 'normal') {
            document.getElementById('unifiedCategoryInput').value = val;
        }
    }

    // Direct Expense: Customer change handler
    function onDirectCustomerChange(customerId) {
        const placeholder = document.getElementById('ordersEmptyPlaceholder');
        const listContainer = document.getElementById('customerOrdersList');
        const grid = document.getElementById('ordersGridCards');

        grid.innerHTML = '';
        document.getElementById('selectedOrderId').value = '';
        document.getElementById('selectedEventId').value = '';

        if (!customerId) {
            placeholder.classList.remove('d-none');
            placeholder.innerHTML = '<i class="bi bi-arrow-up-circle me-1"></i> Please select a customer above to view and link their orders.';
            listContainer.classList.add('d-none');
            return;
        }

        const filtered = allOrders.filter(o => String(o.customerId) === String(customerId));

        if (filtered.length === 0) {
            placeholder.classList.remove('d-none');
            placeholder.innerHTML = '<span class="text-danger fw-semibold"><i class="bi bi-exclamation-circle me-1"></i> No approved orders found for this customer.</span>';
            listContainer.classList.add('d-none');
            return;
        }

        placeholder.classList.add('d-none');
        listContainer.classList.remove('d-none');

        filtered.forEach((o, index) => {
            const isFirst = index === 0;
            const col = document.createElement('div');
            col.className = 'col-12 col-md-6';
            col.innerHTML = `
                <div class="order-select-card ${isFirst ? 'selected' : ''}" onclick="selectDirectOrder('${o.id}', '${o.eventId}', this)">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="badge bg-dark font-monospace" style="font-size: 11px;">${o.orderNo}</span>
                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>${o.eventDate}</small>
                    </div>
                    <div class="fw-bold text-dark text-truncate" style="font-size: 13px;">${o.eventName}</div>
                    <div class="text-muted small text-truncate mb-2" style="font-size: 11px;"><i class="bi bi-geo-alt me-0.5"></i>${o.venue}</div>
                    <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="font-size: 11.5px;">
                        <span class="text-muted">Total: Rs. ${o.total.toLocaleString()}</span>
                        <span class="fw-bold text-danger">Bal: Rs. ${o.balance.toLocaleString()}</span>
                    </div>
                </div>
            `;
            grid.appendChild(col);

            if (isFirst) {
                document.getElementById('selectedOrderId').value = o.id;
                document.getElementById('selectedEventId').value = o.eventId;
            }
        });
    }

    function selectDirectOrder(orderId, eventId, cardEl) {
        document.querySelectorAll('.order-select-card').forEach(c => c.classList.remove('selected'));
        cardEl.classList.add('selected');
        document.getElementById('selectedOrderId').value = orderId;
        document.getElementById('selectedEventId').value = eventId;
    }

    // Dynamic Payment Method Fields
    function onExpenseMethodChange(method) {
        const bankCol = document.getElementById('expenseBankCol');
        const chequeCol = document.getElementById('expenseChequeCol');
        const bankSelect = document.getElementById('expenseBankSelect');
        const chequeInput = document.getElementById('expenseChequeInput');
        const bankLabel = document.getElementById('bankLabelText');

        if (method === 'bank_transfer') {
            bankCol.classList.remove('d-none');
            bankCol.classList.remove('col-md-4');
            bankCol.classList.add('col-md-8');
            chequeCol.classList.add('d-none');
            bankLabel.innerText = 'Debited Bank Account';
            bankSelect.required = true;
            chequeInput.required = false;
        } else if (method === 'cheque') {
            bankCol.classList.remove('d-none');
            bankCol.classList.remove('col-md-8');
            bankCol.classList.add('col-md-4');
            chequeCol.classList.remove('d-none');
            bankLabel.innerText = 'Bank Account';
            bankSelect.required = true;
            chequeInput.required = true;
        } else {
            // Cash
            bankCol.classList.add('d-none');
            chequeCol.classList.add('d-none');
            bankSelect.required = false;
            chequeInput.required = false;
            bankSelect.value = '';
            chequeInput.value = '';
        }
    }

    // AJAX Save New Category
    function saveCategoryAjax() {
        const nameInput = document.getElementById('modalCategoryName');
        const descInput = document.getElementById('modalCategoryDesc');
        const alertBox = document.getElementById('categoryModalAlert');
        const btnSave = document.getElementById('btnSaveCategory');

        const name = nameInput.value.trim();
        const desc = descInput.value.trim();

        if (!name) {
            alertBox.classList.remove('d-none');
            alertBox.innerText = 'Please enter a category name.';
            nameInput.focus();
            return;
        }

        alertBox.classList.add('d-none');
        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';

        fetch("{{ route('expense-categories.quick-store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name, description: desc })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="bi bi-check2 me-1"></i> Save Category';

            if (res.status === 200 && res.body.success) {
                const newCat = res.body.category;
                const select = document.getElementById('normalCategorySelect');

                // Append new option
                const opt = document.createElement('option');
                opt.value = newCat.name;
                opt.text = newCat.name;
                opt.selected = true;
                select.appendChild(opt);

                syncNormalCategory(newCat.name);

                if (typeof jQuery !== 'undefined' && jQuery(select).hasClass('select2-hidden-accessible')) {
                    jQuery(select).val(newCat.name).trigger('change.select2');
                }

                // Reset modal inputs & close
                nameInput.value = '';
                descInput.value = '';
                const modalEl = document.getElementById('addCategoryModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
            } else {
                alertBox.classList.remove('d-none');
                alertBox.innerText = res.body.message || (res.body.errors ? Object.values(res.body.errors).flat().join(', ') : 'Failed to save category.');
            }
        })
        .catch(err => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="bi bi-check2 me-1"></i> Save Category';
            alertBox.classList.remove('d-none');
            alertBox.innerText = 'Network error while saving category.';
        });
    }

    function resetExpenseForm() {
        document.getElementById('customerOrdersList').classList.add('d-none');
        document.getElementById('ordersEmptyPlaceholder').classList.remove('d-none');
        document.getElementById('ordersEmptyPlaceholder').innerHTML = '<i class="bi bi-arrow-up-circle me-1"></i> Please select a customer above to view and link their orders.';
        document.getElementById('selectedOrderId').value = '';
        document.getElementById('selectedEventId').value = '';
        switchExpenseType('direct');
        onExpenseMethodChange('cash');
    }

    // Initializer
    document.addEventListener('DOMContentLoaded', () => {
        const initialType = document.getElementById('expenseTypeInput').value || 'direct';
        switchExpenseType(initialType);

        const custSelect = document.getElementById('directCustomerId');
        if (custSelect.value) {
            onDirectCustomerChange(custSelect.value);
        }

        onExpenseMethodChange(document.getElementById('expensePaymentMethod').value);
    });
</script>
@endsection