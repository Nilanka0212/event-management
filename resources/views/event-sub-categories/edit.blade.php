@extends('layouts.app')
@section('title', 'Edit Event Package / Sub-Category')
@section('page-title', 'Edit Event Package / Sub-Category')

@section('content')
<style>
    /* Custom Styling for Package Creator/Editor */
    .package-creator-card {
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        overflow: hidden;
    }

    .package-header-banner {
        background: #ffffff;
        border-bottom: 1px solid #edf2f7;
        padding: 16px 22px;
    }

    .package-icon-box {
        width: 38px;
        height: 38px;
        background: rgba(72, 70, 70, 0.08);
        border: 1px solid rgba(72, 70, 70, 0.12);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        color: #484646;
        flex-shrink: 0;
    }

    .form-section-title {
        font-size: 13.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        color: #222d3c;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    .form-section-title i {
        font-size: 16px;
        color: #484646;
    }

    .custom-input-label {
        font-weight: 600;
        font-size: 13px;
        color: #334155;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .custom-input-label i {
        color: #94a3b8;
        font-size: 14px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 9px 13px;
        font-size: 13.5px;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #484646;
        box-shadow: 0 0 0 3px rgba(72, 70, 70, 0.15);
    }

    /* Interactive Services Builder Card */
    .services-builder-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
    }

    .services-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .search-input-wrapper {
        position: relative;
        min-width: 260px;
        flex: 1;
        max-width: 400px;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }

    .search-input-wrapper input {
        padding-left: 36px;
        height: 38px;
        font-size: 13px;
    }

    /* Service Table */
    .service-table-container {
        max-height: 480px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .service-table {
        margin-bottom: 0;
    }

    .service-table thead th {
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-bottom: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .service-table tbody tr {
        transition: all 0.18s ease-in-out;
        border-bottom: 1px solid #f1f5f9;
    }

    .service-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .service-table tbody tr.row-selected {
        background-color: #f0fdf4 !important;
        border-left: 3px solid #16a34a;
    }

    .service-table td {
        padding: 11px 14px;
        vertical-align: middle;
        font-size: 13px;
    }

    /* Custom Checkbox */
    .svc-checkbox {
        width: 19px;
        height: 19px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 5px;
        transition: all 0.15s ease;
    }

    .svc-checkbox:checked {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    /* Quantity Stepper */
    .qty-stepper {
        display: inline-flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        overflow: hidden;
        background: #ffffff;
    }

    .qty-btn {
        width: 26px;
        height: 28px;
        background: #f8fafc;
        border: none;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.15s;
    }

    .qty-btn:hover:not(:disabled) {
        background: #e2e8f0;
        color: #0f172a;
    }

    .qty-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .qty-input {
        width: 48px;
        height: 28px;
        border: none;
        text-align: center;
        font-weight: 600;
        font-size: 13px;
        padding: 0;
    }

    .qty-input:focus {
        outline: none;
    }

    /* Financial Analytics Cards */
    .financial-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 16px 18px;
        position: relative;
        transition: all 0.2s ease;
        height: 100%;
    }

    .financial-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
    }

    .financial-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .financial-card-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin: 0;
    }

    .financial-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .financial-card-value {
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -0.3px;
        line-height: 1.2;
    }

    /* Markup Preset Buttons */
    .markup-presets {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }

    .btn-preset {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 12px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-preset:hover {
        background: #484646;
        border-color: #484646;
        color: #ffffff;
    }

    /* Margin Progress Bar */
    .margin-progress-bar {
        height: 6px;
        border-radius: 3px;
        background: #e2e8f0;
        overflow: hidden;
        margin-top: 10px;
    }

    .margin-progress-fill {
        height: 100%;
        width: 0%;
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    /* Sticky Footer Action Bar */
    .package-action-bar {
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .status-switch-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>

<div class="container-fluid px-0">
    <div class="package-creator-card mb-4">
        <!-- Card Header -->
        <div class="package-header-banner d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="package-icon-box">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark" style="font-size: 15.5px; letter-spacing: -0.2px;">Edit Package: {{ $eventSubCategory->name }}</h5>
                    <small class="text-muted" style="font-size: 12px;">Adjust bundled services, modify selling price, and recalculate cost.</small>
                </div>
            </div>
            <div>
                <a href="{{ route('event-sub-categories.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5 px-3">
                    <i class="bi bi-arrow-left"></i> <span>Back to Packages</span>
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                        <strong class="text-dark">Please correct the following errors:</strong>
                    </div>
                    <ul class="mb-0 small ps-4">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('event-sub-categories.update', $eventSubCategory) }}" id="subCategoryForm">
                @csrf
                @method('PUT')

                <!-- Section 1: Basic Information -->
                <div class="mb-4">
                    <div class="form-section-title">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Package Details & Classification</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="custom-input-label">
                                <i class="bi bi-folder2-open"></i>
                                Event Category <span class="text-danger">*</span>
                            </label>
                            <select name="event_category_id" class="form-select" required>
                                <option value="">-- Choose Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('event_category_id', $eventSubCategory->event_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1" style="font-size: 11.5px;">E.g. Wedding, Birthday Party, Corporate Event</small>
                        </div>

                        <div class="col-md-7">
                            <label class="custom-input-label">
                                <i class="bi bi-tag-fill"></i>
                                Package / Sub-Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $eventSubCategory->name) }}" required>
                            <small class="text-muted d-block mt-1" style="font-size: 11.5px;">A catchy and descriptive name that customers and staff will recognize</small>
                        </div>

                        <div class="col-md-8">
                            <label class="custom-input-label">
                                <i class="bi bi-card-text"></i>
                                Description & Highlights
                            </label>
                            <textarea name="description" rows="2" class="form-control" placeholder="Describe the key features, deliverables, and scope of this package...">{{ old('description', $eventSubCategory->description) }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="custom-input-label">
                                <i class="bi bi-toggle-on"></i>
                                Package Status
                            </label>
                            <div class="status-switch-card">
                                <div>
                                    <div class="fw-semibold text-dark small" id="statusLabel">{{ $eventSubCategory->is_active ? 'Active Package' : 'Inactive / Draft' }}</div>
                                    <div class="text-muted" style="font-size: 11px;">Available for quotes and orders</div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $eventSubCategory->is_active) ? 'checked' : '' }} onchange="updateStatusLabel(this.checked)" style="width: 2.5em; height: 1.3em; cursor: pointer;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $attachedServices = $eventSubCategory->subCategoryServices->keyBy('service_id');
                @endphp

                <!-- Section 2: Services Included (Auto Calculates Cost) -->
                <div class="mb-4">
                    <div class="form-section-title">
                        <i class="bi bi-stars"></i>
                        <span>Included Services & Bundle Items</span>
                    </div>

                    <div class="services-builder-box">
                        <!-- Toolbar -->
                        <div class="services-toolbar">
                            <div class="search-input-wrapper">
                                <i class="bi bi-search"></i>
                                <input type="text" id="serviceSearch" class="form-control" placeholder="Search service name..." onkeyup="filterServices()">
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-white border shadow-sm" onclick="selectAllServices(true)">
                                    <i class="bi bi-check-all text-success me-1"></i> Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-white border shadow-sm" onclick="selectAllServices(false)">
                                    <i class="bi bi-x text-danger me-1"></i> Clear Selection
                                </button>
                                <button type="button" class="btn btn-sm btn-white border shadow-sm" id="toggleOnlySelectedBtn" onclick="toggleOnlySelected()">
                                    <i class="bi bi-funnel me-1"></i> <span id="toggleFilterText">Show Selected Only</span>
                                </button>

                                <span class="badge bg-primary text-white px-3 py-2 rounded-pill shadow-sm ms-md-2">
                                    <i class="bi bi-check2-circle me-1"></i> <span id="selectedCount">0</span> Selected
                                </span>
                            </div>
                        </div>

                        <!-- Services Table Container -->
                        <div class="service-table-container">
                            <table class="table service-table align-middle" id="servicesTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="text-center">
                                            <i class="bi bi-check-square"></i>
                                        </th>
                                        <th>Service Details</th>
                                        <th style="width: 150px;" class="text-end">Unit Cost (Rs.)</th>
                                        <th style="width: 130px;" class="text-center">Quantity</th>
                                        <th style="width: 160px;" class="text-end pe-4">Subtotal Cost (Rs.)</th>
                                    </tr>
                                </thead>
                                <tbody id="servicesTableBody">
                                    @forelse($services as $index => $svc)
                                        @php
                                            $isAttached = $attachedServices->has($svc->id);
                                            $attachedItem = $isAttached ? $attachedServices->get($svc->id) : null;
                                            $qty = $attachedItem ? $attachedItem->qty : 1;
                                            $cost = $attachedItem ? $attachedItem->cost_price : $svc->cost_price;
                                        @endphp
                                        <tr class="service-row {{ $isAttached ? 'row-selected' : '' }}" data-id="{{ $svc->id }}" data-name="{{ strtolower($svc->name) }}" data-desc="{{ strtolower($svc->description ?? '') }}">
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input svc-checkbox" 
                                                       id="chk_{{ $svc->id }}" 
                                                       data-index="{{ $index }}"
                                                       {{ $isAttached ? 'checked' : '' }}
                                                       onchange="toggleServiceRow({{ $index }}, this)">
                                            </td>
                                            <td>
                                                <label for="chk_{{ $svc->id }}" class="fw-bold text-dark mb-0 cursor-pointer d-block" style="font-size: 13.5px;">
                                                    {{ $svc->name }}
                                                </label>
                                                @if($svc->description)
                                                    <span class="text-muted d-block mt-0" style="font-size: 11.5px; line-height: 1.3;">
                                                        {{ $svc->description }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <input type="hidden" name="services[{{ $index }}][service_id]" value="{{ $svc->id }}" class="svc-id-input" {{ $isAttached ? '' : 'disabled' }}>
                                                <span class="fw-semibold text-secondary" style="font-size: 13px;">
                                                    {{ number_format($cost, 2) }}
                                                </span>
                                                <input type="hidden" name="services[{{ $index }}][cost_price]" 
                                                       value="{{ $cost }}" 
                                                       class="svc-cost-input" 
                                                       {{ $isAttached ? '' : 'disabled' }}>
                                            </td>
                                            <td class="text-center">
                                                <div class="qty-stepper">
                                                    <button type="button" class="qty-btn" onclick="stepQty({{ $index }}, -1)" {{ $isAttached ? '' : 'disabled' }} id="qtyMinus_{{ $index }}">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" min="1" step="1" name="services[{{ $index }}][qty]" 
                                                           value="{{ $qty }}" 
                                                           class="qty-input svc-qty-input" 
                                                           id="qtyInput_{{ $index }}"
                                                           {{ $isAttached ? '' : 'disabled' }}
                                                           oninput="calculateTotalCost()">
                                                    <button type="button" class="qty-btn" onclick="stepQty({{ $index }}, 1)" {{ $isAttached ? '' : 'disabled' }} id="qtyPlus_{{ $index }}">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-dark svc-line-total" style="font-size: 13.5px;">
                                                {{ $isAttached ? number_format($cost * $qty, 2) : '0.00' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="emptyServicesRow">
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <div class="mb-2">
                                                    <i class="bi bi-inbox text-secondary" style="font-size: 36px;"></i>
                                                </div>
                                                <div class="fw-semibold">No services configured in your system.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div id="noMatchMessage" class="text-center py-4 text-muted d-none">
                                <i class="bi bi-search me-1"></i> No matching services found for "<span id="searchKeyword"></span>".
                            </div>
                        </div>

                        <!-- Summary footer inside the box -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between p-3 mt-3 bg-white rounded-3 border">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle text-primary fs-5"></i>
                                <span class="small text-muted">
                                    Cost prices are automatically aggregated from active vendor rates.
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-semibold text-secondary small">Total Package Direct Cost:</span>
                                <span class="fs-5 fw-bold text-primary" id="totalCostDisplay">Rs. {{ number_format($eventSubCategory->cost_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Financial Analytics & Profitability Forecast -->
                <div class="mb-4">
                    <div class="form-section-title">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Pricing & Profit Margin Analysis</span>
                    </div>

                    <div class="row g-3">
                        <!-- Total Cost Card -->
                        <div class="col-md-4">
                            <div class="financial-card">
                                <div class="financial-card-header">
                                    <span class="financial-card-title">Package Cost Price</span>
                                    <div class="financial-card-icon bg-light text-secondary">
                                        <i class="bi bi-calculator-fill"></i>
                                    </div>
                                </div>
                                <div class="financial-card-value text-dark" id="displayCostCard">
                                    Rs. {{ number_format($eventSubCategory->cost_price, 2) }}
                                </div>
                                <input type="hidden" id="packageCostPriceInput" value="{{ $eventSubCategory->cost_price }}">
                                <div class="text-muted mt-2" style="font-size: 11.5px;">
                                    Auto-calculated from <span id="displaySelectedCount">0</span> selected services.
                                </div>
                            </div>
                        </div>

                        <!-- Selling Price Input Card -->
                        <div class="col-md-4">
                            <div class="financial-card" style="border-color: #cbd5e1;">
                                <div class="financial-card-header">
                                    <span class="financial-card-title text-primary">Selling Price to Client <span class="text-danger">*</span></span>
                                    <div class="financial-card-icon bg-primary-subtle text-primary">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <!-- <span class="input-group-text bg-white fw-bold text-muted border-end-0">Rs.</span> -->
                                    <input type="number" step="0.01" min="0" name="sell_price" id="sellPriceInput" 
                                           class="form-control form-control-lg fw-bold text-success border-start-0" 
                                           value="{{ old('sell_price', $eventSubCategory->sell_price) }}" 
                                           placeholder="0.00" 
                                           required 
                                           oninput="calculateMargin()">
                                </div>
                                
                                <!-- Quick Markup Presets -->
                                <!-- <div class="markup-presets">
                                    <span class="text-muted" style="font-size: 11px; align-self: center;">Quick Margin:</span>
                                    <button type="button" class="btn-preset" onclick="applyMarkup(15)">+15%</button>
                                    <button type="button" class="btn-preset" onclick="applyMarkup(25)">+25%</button>
                                    <button type="button" class="btn-preset" onclick="applyMarkup(35)">+35%</button>
                                    <button type="button" class="btn-preset" onclick="applyMarkup(50)">+50%</button>
                                </div> -->
                            </div>
                        </div>

                        <!-- Profit & Margin Card -->
                        <div class="col-md-4">
                            <div class="financial-card">
                                <div class="financial-card-header">
                                    <span class="financial-card-title">Estimated Profit</span>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" id="marginBadge">
                                        Profitable
                                    </span>
                                </div>
                                <div class="d-flex align-items-baseline justify-content-between">
                                    <div class="financial-card-value text-success" id="marginProfit">
                                        Rs. 0.00
                                    </div>
                                    <div class="fw-bold small text-muted" id="marginPercent">
                                        0.0% margin
                                    </div>
                                </div>
                                <!-- Progress Bar Indicator -->
                                <div class="margin-progress-bar">
                                    <div class="margin-progress-fill bg-success" id="marginProgressFill"></div>
                                </div>
                                <div class="d-flex justify-content-between text-muted mt-2" style="font-size: 11px;">
                                    <span id="marginStatusText">Target healthy margin is 20%+</span>
                                    <span id="marginMarkupText">0% markup</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="package-action-bar rounded-3">
                    <div class="d-flex align-items-center gap-2">
                        <!-- <span class="badge bg-light text-dark border px-3 py-2">
                            <i class="bi bi-shield-check text-success me-1"></i> Data validated upon submit
                        </span> -->
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('event-sub-categories.index') }}" class="btn btn-outline-secondary px-4 fw-semibold">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" id="submitPackageBtn">
                            <i class="bi bi-check2-circle me-1"></i> Update Package
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let showOnlySelected = false;

    function updateStatusLabel(isChecked) {
        const label = document.getElementById('statusLabel');
        if (label) {
            label.innerText = isChecked ? 'Active Package' : 'Inactive / Draft';
        }
    }

    function toggleServiceRow(index, checkbox) {
        const row = checkbox.closest('tr');
        const idInput = row.querySelector('.svc-id-input');
        const costInput = row.querySelector('.svc-cost-input');
        const qtyInput = row.querySelector('.svc-qty-input');
        const minusBtn = document.getElementById(`qtyMinus_${index}`);
        const plusBtn = document.getElementById(`qtyPlus_${index}`);

        if (checkbox.checked) {
            idInput.disabled = false;
            costInput.disabled = false;
            qtyInput.disabled = false;
            if (minusBtn) minusBtn.disabled = false;
            if (plusBtn) plusBtn.disabled = false;
            row.classList.add('row-selected');
        } else {
            idInput.disabled = true;
            costInput.disabled = true;
            qtyInput.disabled = true;
            if (minusBtn) minusBtn.disabled = true;
            if (plusBtn) plusBtn.disabled = true;
            row.classList.remove('row-selected');
        }

        calculateTotalCost();

        if (showOnlySelected) {
            applyFilter();
        }
    }

    function stepQty(index, delta) {
        const input = document.getElementById(`qtyInput_${index}`);
        if (!input || input.disabled) return;

        let val = parseInt(input.value) || 1;
        val += delta;
        if (val < 1) val = 1;
        input.value = val;
        calculateTotalCost();
    }

    function calculateTotalCost() {
        let totalCost = 0;
        let count = 0;

        document.querySelectorAll('.service-row').forEach(row => {
            const checkbox = row.querySelector('.svc-checkbox');
            const lineTotalCell = row.querySelector('.svc-line-total');

            if (checkbox && checkbox.checked) {
                count++;
                const cost = parseFloat(row.querySelector('.svc-cost-input').value) || 0;
                const qty = parseInt(row.querySelector('.svc-qty-input').value) || 1;
                const lineTotal = cost * qty;
                lineTotalCell.innerText = lineTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                totalCost += lineTotal;
            } else {
                lineTotalCell.innerText = '0.00';
            }
        });

        document.getElementById('selectedCount').innerText = count;
        document.getElementById('displaySelectedCount').innerText = count;
        
        const formattedCost = 'Rs. ' + totalCost.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('totalCostDisplay').innerText = formattedCost;
        document.getElementById('displayCostCard').innerText = formattedCost;
        document.getElementById('packageCostPriceInput').value = totalCost.toFixed(2);

        calculateMargin();
    }

    function calculateMargin() {
        const cost = parseFloat(document.getElementById('packageCostPriceInput').value) || 0;
        const sell = parseFloat(document.getElementById('sellPriceInput').value) || 0;
        const profit = sell - cost;
        const percent = sell > 0 ? ((profit / sell) * 100).toFixed(1) : 0;
        const markup = cost > 0 ? ((profit / cost) * 100).toFixed(1) : 0;

        const profitEl = document.getElementById('marginProfit');
        const percentEl = document.getElementById('marginPercent');
        const badgeEl = document.getElementById('marginBadge');
        const progressEl = document.getElementById('marginProgressFill');
        const statusTextEl = document.getElementById('marginStatusText');
        const markupTextEl = document.getElementById('marginMarkupText');

        profitEl.innerText = 'Rs. ' + profit.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        percentEl.innerText = percent + '% margin';
        markupTextEl.innerText = markup + '% markup';

        // Clamp width for visual progress bar
        let visualWidth = Math.max(0, Math.min(100, percent));
        progressEl.style.width = visualWidth + '%';

        if (sell === 0 && cost === 0) {
            profitEl.className = 'financial-card-value text-secondary';
            badgeEl.className = 'badge bg-secondary-subtle text-secondary border border-secondary-subtle';
            badgeEl.innerText = 'No Pricing';
            progressEl.className = 'margin-progress-fill bg-secondary';
            statusTextEl.innerText = 'Enter selling price';
        } else if (profit > 0) {
            if (percent >= 30) {
                profitEl.className = 'financial-card-value text-success';
                badgeEl.className = 'badge bg-success-subtle text-success border border-success-subtle';
                badgeEl.innerText = 'High Margin';
                progressEl.className = 'margin-progress-fill bg-success';
                statusTextEl.innerText = 'Excellent profitability';
            } else if (percent >= 15) {
                profitEl.className = 'financial-card-value text-primary';
                badgeEl.className = 'badge bg-primary-subtle text-primary border border-primary-subtle';
                badgeEl.innerText = 'Healthy Margin';
                progressEl.className = 'margin-progress-fill bg-primary';
                statusTextEl.innerText = 'Good profitability';
            } else {
                profitEl.className = 'financial-card-value text-warning';
                badgeEl.className = 'badge bg-warning-subtle text-warning border border-warning-subtle';
                badgeEl.innerText = 'Low Margin';
                progressEl.className = 'margin-progress-fill bg-warning';
                statusTextEl.innerText = 'Margin is below 15%';
            }
        } else if (profit === 0) {
            profitEl.className = 'financial-card-value text-muted';
            badgeEl.className = 'badge bg-secondary-subtle text-secondary border border-secondary-subtle';
            badgeEl.innerText = 'Break-even';
            progressEl.className = 'margin-progress-fill bg-secondary';
            statusTextEl.innerText = 'Zero profit';
        } else {
            profitEl.className = 'financial-card-value text-danger';
            badgeEl.className = 'badge bg-danger-subtle text-danger border border-danger-subtle';
            badgeEl.innerText = 'Loss';
            progressEl.className = 'margin-progress-fill bg-danger';
            progressEl.style.width = '100%';
            statusTextEl.innerText = 'Selling price is below cost';
        }
    }

    function applyMarkup(markupPercent) {
        const cost = parseFloat(document.getElementById('packageCostPriceInput').value) || 0;
        if (cost <= 0) {
            alert('Please select at least one service to establish a package cost price first.');
            return;
        }

        const recommendedSell = cost * (1 + (markupPercent / 100));
        const roundedSell = Math.ceil(recommendedSell);
        document.getElementById('sellPriceInput').value = roundedSell.toFixed(2);
        calculateMargin();
    }

    function selectAllServices(select) {
        document.querySelectorAll('.service-row').forEach(row => {
            if (row.style.display !== 'none') {
                const checkbox = row.querySelector('.svc-checkbox');
                const index = checkbox.dataset.index;
                checkbox.checked = select;
                toggleServiceRow(index, checkbox);
            }
        });
        calculateTotalCost();
    }

    function toggleOnlySelected() {
        showOnlySelected = !showOnlySelected;
        const btn = document.getElementById('toggleOnlySelectedBtn');
        const textSpan = document.getElementById('toggleFilterText');

        if (showOnlySelected) {
            btn.classList.remove('btn-white');
            btn.classList.add('btn-primary');
            textSpan.innerText = 'Show All Services';
        } else {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-white');
            textSpan.innerText = 'Show Selected Only';
        }

        applyFilter();
    }

    function filterServices() {
        applyFilter();
    }

    function applyFilter() {
        const query = (document.getElementById('serviceSearch').value || '').trim().toLowerCase();
        let visibleCount = 0;

        document.querySelectorAll('.service-row').forEach(row => {
            const name = row.dataset.name || '';
            const desc = row.dataset.desc || '';
            const checkbox = row.querySelector('.svc-checkbox');
            const isSelected = checkbox && checkbox.checked;

            const matchesQuery = !query || name.includes(query) || desc.includes(query);
            const matchesSelected = !showOnlySelected || isSelected;

            if (matchesQuery && matchesSelected) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noMatchMsg = document.getElementById('noMatchMessage');
        const searchKeywordSpan = document.getElementById('searchKeyword');

        if (visibleCount === 0) {
            noMatchMsg.classList.remove('d-none');
            searchKeywordSpan.innerText = query || (showOnlySelected ? 'selected items' : '');
        } else {
            noMatchMsg.classList.add('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateTotalCost();
        const activeSwitch = document.getElementById('isActive');
        if (activeSwitch) {
            updateStatusLabel(activeSwitch.checked);
        }
    });
</script>
@endsection