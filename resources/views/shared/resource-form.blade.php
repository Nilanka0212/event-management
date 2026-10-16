@extends('layouts.app')

@php
    $currentRoute = request()->route()?->getName();
    $lineItemConfig = match ($currentRoute) {
        'events.create', 'events.edit' => [
            'service_prefix' => 'additional_services',
            'service_title' => 'Additional services',
            'existing_services' => data_get($record ?? null, 'additionalServices', []),
            'charge_prefix' => 'other_charges',
            'existing_charges' => data_get($record ?? null, 'otherCharges', []),
        ],
        'orders.create', 'orders.edit' => [
            'service_prefix' => 'services',
            'service_title' => 'Order services',
            'existing_services' => data_get($record ?? null, 'services', []),
            'charge_prefix' => 'other_charges',
            'existing_charges' => data_get($record ?? null, 'otherCharges', []),
        ],
        'quotations.create', 'quotations.edit' => [
            'service_prefix' => 'services',
            'service_title' => 'Quoted services',
            'existing_services' => data_get($record ?? null, 'services', []),
            'charge_prefix' => 'other_charges',
            'existing_charges' => data_get($record ?? null, 'otherCharges', []),
        ],
        'event-sub-categories.create', 'event-sub-categories.edit' => [
            'service_prefix' => 'services',
            'service_title' => 'Included services',
            'service_mode' => 'cost',
            'existing_services' => data_get($record ?? null, 'subCategoryServices', []),
        ],
        default => null,
    };
@endphp

@section('title', $formTitle)
@section('page-title', $formTitle)

@section('content')
<div class="page-header"><div><h4>{{ $formTitle }}</h4><p class="text-muted mb-0">{{ $formDescription ?? 'Enter the details below.' }}</p></div><a href="{{ route($resourceRoute . '.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a></div>
<form method="POST" action="{{ $formAction }}">
    @csrf
    @if(!empty($formMethod) && $formMethod !== 'POST')@method($formMethod)@endif
    <div class="card"><div class="card-body"><div class="row g-3">
        @foreach($fields as $field)
            @php
                $fieldValue = old($field['name'], data_get($record ?? null, $field['value_key'] ?? $field['name'], $field['value'] ?? ''));
                $fieldType = $field['type'] ?? 'text';
                $fieldWidth = $field['width'] ?? 6;
                if ($fieldType === 'date' && is_object($fieldValue) && method_exists($fieldValue, 'format')) {
                    $fieldValue = $fieldValue->format('Y-m-d');
                }
            @endphp
            <div class="col-12 col-md-{{ $fieldWidth }}">
                <label class="form-label" for="{{ str_replace(['[', ']'], ['_', ''], $field['name']) }}">{{ $field['label'] }} @if(!empty($field['required']))<span class="text-danger">*</span>@endif</label>
                @if($fieldType === 'textarea')
                    <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="{{ $field['rows'] ?? 3 }}" class="form-control @error($field['name']) is-invalid @enderror" @required(!empty($field['required']))>{{ $fieldValue }}</textarea>
                @elseif($fieldType === 'select')
                    <select id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-select @error($field['name']) is-invalid @enderror" @required(!empty($field['required']))>
                        @if(empty($field['required']))<option value="">Select {{ strtolower($field['label']) }}</option>@endif
                        @foreach($field['options'] ?? [] as $optionValue => $optionLabel)<option value="{{ $optionValue }}" @selected((string) $fieldValue === (string) $optionValue)>{{ $optionLabel }}</option>@endforeach
                    </select>
                @elseif($fieldType === 'checkbox')
                    <div class="form-check mt-2"><input id="{{ $field['name'] }}" name="{{ $field['name'] }}" value="1" type="checkbox" class="form-check-input" @checked((bool) $fieldValue)><label class="form-check-label" for="{{ $field['name'] }}">{{ $field['checkLabel'] ?? 'Enabled' }}</label></div>
                @else
                    <input id="{{ $field['name'] }}" name="{{ $field['name'] }}" type="{{ $fieldType }}" value="{{ $fieldValue }}" class="form-control @error($field['name']) is-invalid @enderror" @required(!empty($field['required'])) @if(isset($field['min'])) min="{{ $field['min'] }}" @endif @if(isset($field['step'])) step="{{ $field['step'] }}" @endif @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif>
                @endif
                @error($field['name'])<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        @endforeach
        @if(!empty($lineItemConfig))
            @include('shared.line-items', ['config' => $lineItemConfig])
        @endif
    </div></div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2"><a href="{{ route($resourceRoute . '.index') }}" class="btn btn-light">Cancel</a><button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i> {{ $submitLabel ?? 'Save' }}</button></div>
    </div>
</form>
@endsection