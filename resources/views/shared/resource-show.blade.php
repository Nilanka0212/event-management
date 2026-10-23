@extends('layouts.app')

@section('title', $detailTitle)
@section('page-title', $detailTitle)

@section('content')
<!-- Page Header (Tritcal Style) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
            style="width: 44px; height: 44px; background: linear-gradient(135deg, #484646 0%, #3a3838 100%); font-size: 20px;">
            <i class="bi bi-file-earmark-text"></i>
        </div>
        <div>
            <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">{{ $detailTitle }}</h4>
            <p class="text-muted small mb-0">{{ $detailSubtitle ?? 'Record details and configuration specifications.' }}</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route($resourceRoute . '.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
        @if(!empty($editRoute))
            <a href="{{ route($resourceRoute . '.edit', $record) }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-pencil"></i> Edit Record
            </a>
        @endif
    </div>
</div>

<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden; border: 1px solid #edf2f7 !important;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
            <i class="bi bi-info-circle text-secondary"></i> Detailed Information
        </h6>
        @if(isset($record->updated_at))
            <span class="text-muted small">Updated {{ $record->updated_at->diffForHumans() }}</span>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <tbody>
                    @foreach($details as $detail)
                        @php($value = data_get($record, $detail['key']))
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td class="ps-4 text-muted fw-semibold" style="width: 250px; font-size: 12.5px; background: #fafbfc;">
                                {{ $detail['label'] }}
                            </td>
                            <td class="ps-4 pe-4 text-dark" style="font-size: 13px;">
                                @if(($detail['type'] ?? '') === 'money')
                                    <strong class="text-dark">Rs. {{ number_format((float) $value, 2) }}</strong>
                                @elseif(($detail['type'] ?? '') === 'date')
                                    <span class="text-nowrap">{{ $value ? \Illuminate\Support\Carbon::parse($value)->format('M d, Y') : '—' }}</span>
                                @elseif(($detail['type'] ?? '') === 'status')
                                    <span class="badge-status badge-{{ $value }}">
                                        <i class="bi bi-dot"></i> {{ ucfirst((string) $value) }}
                                    </span>
                                @elseif(($detail['type'] ?? '') === 'boolean')
                                    @if($value)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 11px; font-weight: 600;">
                                            <i class="bi bi-check-circle me-1"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1" style="font-size: 11px; font-weight: 600;">
                                            <i class="bi bi-dash-circle me-1"></i> Inactive
                                        </span>
                                    @endif
                                @else
                                    {{ $value ?: '—' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection