@extends('layouts.app')

@section('title', $detailTitle)
@section('page-title', $detailTitle)

@section('content')
<div class="page-header"><div><h4>{{ $detailTitle }}</h4><p class="text-muted mb-0">{{ $detailSubtitle ?? 'Record details' }}</p></div><div class="d-flex gap-2"><a href="{{ route($resourceRoute . '.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>@if(!empty($editRoute))<a href="{{ route($resourceRoute . '.edit', $record) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Edit</a>@endif</div></div>
<div class="card"><div class="card-body"><dl class="row mb-0">
    @foreach($details as $detail)
        @php($value = data_get($record, $detail['key']))
        <dt class="col-sm-4 col-lg-3 text-muted py-2">{{ $detail['label'] }}</dt>
        <dd class="col-sm-8 col-lg-9 py-2 mb-0">@if(($detail['type'] ?? '') === 'money'){{ number_format((float) $value, 2) }}@elseif(($detail['type'] ?? '') === 'date'){{ $value ? \Illuminate\Support\Carbon::parse($value)->format('M j, Y') : '—' }}@elseif(($detail['type'] ?? '') === 'status')<span class="badge-status badge-{{ $value }}">{{ ucfirst((string) $value) }}</span>@else{{ $value ?: '—' }}@endif</dd>
    @endforeach
</dl></div></div>
@endsection