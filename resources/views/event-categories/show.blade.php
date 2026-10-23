@extends('layouts.app')
@section('title', $eventCategory->name . ' - Event Category Details')
@section('page-title', 'Event Category Details')

@section('content')
<!-- Page Header (Tritcal Style) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
            style="width: 44px; height: 44px; background: linear-gradient(135deg, #484646 0%, #3a3838 100%); font-size: 20px;">
            <i class="bi bi-folder2-open"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">{{ $eventCategory->name }}</h4>
                <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 11px; font-weight: 700;">
                    #CAT-{{ str_pad($eventCategory->id, 4, '0', STR_PAD_LEFT) }}
                </span>
                @if($eventCategory->is_active)
                    <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center gap-1.5 px-2 py-1" style="font-size: 11px; font-weight: 600;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #22c55e;"></span> Active
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle d-inline-flex align-items-center gap-1.5 px-2 py-1" style="font-size: 11px; font-weight: 600;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #94a3b8;"></span> Inactive
                    </span>
                @endif
            </div>
            <p class="text-muted small mb-0">Event category configuration and package grouping.</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('event-categories.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Categories
        </a>
        <a href="{{ route('event-categories.edit', $eventCategory) }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-pencil"></i> Edit Category
        </a>
    </div>
</div>

<!-- Main Details Card (Tritcal Clean Details Style) -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden; border: 1px solid #edf2f7 !important;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
            <i class="bi bi-info-circle text-secondary"></i> Category Information
        </h6>
        <span class="text-muted small">ID: #{{ $eventCategory->id }}</span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-tag text-secondary me-2"></i>Category Name
                        </td>
                        <td class="ps-4 pe-4 text-dark fw-bold" style="font-size: 13.5px;">
                            {{ $eventCategory->name }}
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-upc-scan text-secondary me-2"></i>Category Code
                        </td>
                        <td class="ps-4 pe-4 text-dark font-monospace" style="font-size: 13px;">
                            #CAT-{{ str_pad($eventCategory->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-activity text-secondary me-2"></i>Status
                        </td>
                        <td class="ps-4 pe-4">
                            @if($eventCategory->is_active)
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
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-collection text-secondary me-2"></i>Sub-Categories / Packages
                        </td>
                        <td class="ps-4 pe-4 text-dark" style="font-size: 13px;">
                            <span class="badge bg-light text-dark border px-2.5 py-1 fw-bold" style="font-size: 12px;">
                                {{ $eventCategory->subCategories->count() }} Linked Packages
                            </span>
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold align-top pt-3" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-card-text text-secondary me-2"></i>Description
                        </td>
                        <td class="ps-4 pe-4 pt-3 pb-3" style="font-size: 13px; color: #334155; line-height: 1.6;">
                            @if(!empty($eventCategory->description))
                                {{ $eventCategory->description }}
                            @else
                                <span class="text-muted fst-italic">No description provided for this category.</span>
                            @endif
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-calendar-plus text-secondary me-2"></i>Created Date
                        </td>
                        <td class="ps-4 pe-4 text-dark" style="font-size: 12.5px;">
                            {{ $eventCategory->created_at?->format('F d, Y - h:i A') ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="ps-4 text-muted fw-semibold" style="width: 260px; font-size: 12.5px; background: #fafbfc;">
                            <i class="bi bi-clock-history text-secondary me-2"></i>Last Updated
                        </td>
                        <td class="ps-4 pe-4 text-dark" style="font-size: 12.5px;">
                            {{ $eventCategory->updated_at?->format('F d, Y - h:i A') ?? '—' }}
                            @if($eventCategory->updated_at)
                                <small class="text-muted ms-2">({{ $eventCategory->updated_at->diffForHumans() }})</small>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white py-3 border-top d-flex align-items-center justify-content-between">
        <a href="{{ route('event-categories.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Categories
        </a>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('event-categories.edit', $eventCategory) }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-pencil"></i> Edit Category
            </a>
            <form method="POST" action="{{ route('event-categories.destroy', $eventCategory) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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