@extends('layouts.app')

@section('title', $resourceTitle)
@section('page-title', $resourceTitle)

@section('content')
<div class="page-header">
    <div><h4>{{ $resourceTitle }}</h4><p class="text-muted mb-0">{{ $resourceDescription ?? 'Browse and manage records.' }}</p></div>
    @if(!empty($createRoute))<a href="{{ route($createRoute) }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> {{ $createLabel ?? 'Add new' }}</a>@endif
</div>
<div class="card">
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-5"><label class="form-label" for="search">Search</label><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search {{ strtolower($resourceTitle) }}"></div>
            @if(!empty($statusFilter))
                <div class="col-12 col-md-3"><label class="form-label" for="status">Status</label><select id="status" name="status" class="form-select"><option value="">All statuses</option>@foreach($statusFilter as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
            @endif
            <div class="col-auto"><button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search me-1"></i> Filter</button></div>
            @if(request()->hasAny(['search', 'status']))<div class="col-auto"><a href="{{ route($resourceRoute . '.index') }}" class="btn btn-link">Clear</a></div>@endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr>@foreach($columns as $column)<th>{{ $column['label'] }}</th>@endforeach<th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($resourceItems as $item)
                    <tr>
                        @foreach($columns as $column)
                            @php($value = data_get($item, $column['key']))
                            <td>
                                @if(($column['type'] ?? '') === 'money'){{ number_format((float) $value, 2) }}
                                @elseif(($column['type'] ?? '') === 'date'){{ $value ? \Illuminate\Support\Carbon::parse($value)->format('M j, Y') : '—' }}
                                @elseif(($column['type'] ?? '') === 'status')<span class="badge-status badge-{{ $value }}">{{ ucfirst((string) $value) }}</span>
                                @elseif(($column['type'] ?? '') === 'boolean')<span class="badge-status badge-{{ $value ? 'accepted' : 'draft' }}">{{ $value ? 'Active' : 'Inactive' }}</span>
                                @else{{ $value ?: '—' }}@endif
                            </td>
                        @endforeach
                        <td class="text-end text-nowrap">
                            @if(!empty($showRoute))<a href="{{ route($resourceRoute . '.show', $item) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="View"><i class="bi bi-eye"></i></a>@endif
                            @if(!empty($editRoute))<a href="{{ route($resourceRoute . '.edit', $item) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>@endif
                            @if(!empty($deleteRoute))<form method="POST" action="{{ route($resourceRoute . '.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Delete this record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger btn-icon" title="Delete"><i class="bi bi-trash"></i></button></form>@endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ count($columns) + 1 }}" class="text-center text-muted py-5">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($resourceItems, 'links'))<div class="card-body">{{ $resourceItems->withQueryString()->links() }}</div>@endif
</div>
@endsection