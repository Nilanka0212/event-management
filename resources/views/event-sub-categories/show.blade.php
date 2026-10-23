@extends('layouts.app')
@section('title', $eventSubCategory->name . ' - Event Sub-Category Details')
@section('page-title', 'Sub-Category / Package Details')

@section('content')
<!-- Page Header (Tritcal Style) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
            style="width: 44px; height: 44px; background: linear-gradient(135deg, #484646 0%, #3a3838 100%); font-size: 20px;">
            <i class="bi bi-box-seam"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">{{ $eventSubCategory->name }}</h4>
                <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 11px; font-weight: 700;">
                    #PKG-{{ str_pad($eventSubCategory->id, 4, '0', STR_PAD_LEFT) }}
                </span>
                @if($eventSubCategory->is_active)
                    <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center gap-1.5 px-2 py-1" style="font-size: 11px; font-weight: 600;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #22c55e;"></span> Active
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle d-inline-flex align-items-center gap-1.5 px-2 py-1" style="font-size: 11px; font-weight: 600;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #94a3b8;"></span> Inactive
                    </span>
                @endif
            </div>
            <p class="text-muted small mb-0">Event package preset details and financial pricing.</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('event-sub-categories.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Sub-Categories
        </a>
        <a href="{{ route('event-sub-categories.edit', $eventSubCategory) }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-pencil"></i> Edit Sub-Category
        </a>
    </div>
</div>

<!-- Main Details Card (Tritcal Clean Details Style) -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden; border: 1px solid #edf2f7 !important;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
            <i class="bi bi-info-circle text-secondary"></i> Package / Sub-Category Information
        </h6>
        <span class="text-muted small">ID: #{{ $eventSubCategory->id }}</span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-tag text-secondary me-2"></i>Package / Sub-Category Name
                        </td>
                        <td class="ps-4 pe-4 text-dark fw-bold" style="font-size: 13.5px;">
                            {{ $eventSubCategory->name }}
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-upc-scan text-secondary me-2"></i>Package Code
                        </td>
                        <td class="ps-4 pe-4 text-dark font-monospace" style="font-size: 13px;">
                            #PKG-{{ str_pad($eventSubCategory->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-folder2 text-secondary me-2"></i>Event Category
                        </td>
                        <td class="ps-4 pe-4">
                            <span class="badge bg-light text-dark border px-2.5 py-1" style="font-size: 12px; font-weight: 600;">
                                <i class="bi bi-bookmark-fill text-secondary me-1"></i>{{ $eventSubCategory->category->name ?? 'General' }}
                            </span>
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-cash-stack text-secondary me-2"></i>Selling Price
                        </td>
                        <td class="ps-4 pe-4" style="font-size: 14px;">
                            <span class="fw-bold text-dark">Rs. {{ number_format((float)$eventSubCategory->sell_price, 2) }}</span>
                            <small class="text-muted ms-2">(Customer Package Rate)</small>
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-receipt-cutoff text-secondary me-2"></i>Cost Price (Internal)
                        </td>
                        <td class="ps-4 pe-4 text-muted" style="font-size: 13.5px;">
                            Rs. {{ number_format((float)$eventSubCategory->cost_price, 2) }}
                            @php($margin = (float)$eventSubCategory->sell_price - (float)$eventSubCategory->cost_price)
                            @if($margin > 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-2 px-2 py-0.5" style="font-size: 11px;">
                                    Profit: +Rs. {{ number_format($margin, 2) }}
                                </span>
                            @endif
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-activity text-secondary me-2"></i>Status
                        </td>
                        <td class="ps-4 pe-4">
                            @if($eventSubCategory->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 11.5px; font-weight: 600;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Active
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1" style="font-size: 11.5px; font-weight: 600;">
                                    <i class="bi bi-x-circle-fill me-1"></i> Inactive
                                </span>
                            @endif
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold align-top pt-3" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-card-text text-secondary me-2"></i>Description
                        </td>
                        <td class="ps-4 pe-4 pt-3 pb-3" style="font-size: 13px; color: #334155; line-height: 1.6;">
                            @if(!empty($eventSubCategory->description))
                                {{ $eventSubCategory->description }}
                            @else
                                <span class="text-muted fst-italic">No description provided for this sub-category.</span>
                            @endif
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-calendar-plus text-secondary me-2"></i>Created Date
                        </td>
                        <td class="ps-4 pe-4 text-dark" style="font-size: 12.5px;">
                            {{ $eventSubCategory->created_at?->format('F d, Y - h:i A') ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-clock-history text-secondary me-2"></i>Last Updated
                        </td>
                        <td class="ps-4 pe-4 text-dark" style="font-size: 12.5px;">
                            {{ $eventSubCategory->updated_at?->format('F d, Y - h:i A') ?? '—' }}
                            @if($eventSubCategory->updated_at)
                                <small class="text-muted ms-2">({{ $eventSubCategory->updated_at->diffForHumans() }})</small>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white py-3 border-top d-flex align-items-center justify-content-between">
        <a href="{{ route('event-sub-categories.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Sub-Categories
        </a>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('event-sub-categories.edit', $eventSubCategory) }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-pencil"></i> Edit Sub-Category
            </a>
            <form method="POST" action="{{ route('event-sub-categories.destroy', $eventSubCategory) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sub-category?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-light-danger shadow-sm">
                    <i class="bi bi-trash3"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection