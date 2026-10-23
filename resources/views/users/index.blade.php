@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<!-- Header Area (Tritcal Project Management Structure) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">System Users</h4>
            @if(isset($users) && method_exists($users, 'total'))
                <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 11.5px; font-weight: 700;">
                    <i class="bi bi-people me-1 text-secondary"></i>{{ $users->total() }} users
                </span>
            @endif
        </div>
        <p class="text-muted small mb-0">Manage system administrators, managers, accountants, and staff members.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm d-inline-flex align-items-center gap-1.5">
        <i class="bi bi-person-plus-fill"></i> Add New User
    </a>
</div>

<!-- Project Management Filter & Toolbar Card -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden; border: 1px solid #edf2f7 !important;">
    <div class="card-body border-bottom p-3 bg-white">
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-sm-6 col-md-5 col-lg-4">
                <label class="form-label small fw-bold text-muted mb-1" for="userSearch">
                    <i class="bi bi-search me-1"></i> Quick Search
                </label>
                <input id="userSearch" type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label small fw-bold text-muted mb-1" for="userRole">
                    <i class="bi bi-funnel me-1"></i> Role Filter
                </label>
                <select id="userRole" name="role" class="form-select">
                    <option value="">All Roles</option>
                    @foreach(['Admin', 'Manager', 'Accountant', 'Staff'] as $r)
                        <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-auto d-flex align-items-center gap-2 mt-2 mt-sm-0">
                <button type="submit" class="btn btn-primary px-3">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table (Tritcal Custom Table Style) -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted" style="border-bottom: 2px solid #edf2f7;">
                <tr>
                    <th class="ps-4">User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end pe-4 text-nowrap" style="width: 1%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0"
                                    style="width: 32px; height: 32px; font-size: 12.5px; background: {{ $user->role === 'Admin' ? 'linear-gradient(135deg, #484646, #3a3838)' : ($user->role === 'Manager' ? 'linear-gradient(135deg, #52525b, #71717a)' : 'linear-gradient(135deg, #64748b, #94a3b8)') }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 13px; line-height: 1.2;">{{ $user->name }}</div>
                                    <div class="text-muted" style="font-size: 11px;"><i class="bi bi-person me-1"></i>{{ $user->username }}</div>
                                    @if($user->id === Auth::id())
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 9.5px; padding: 1px 5px;">Current User</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size: 12.5px;"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</span>
                        </td>
                        <td>
                            @php
                                $roleClass = match($user->role) {
                                    'Admin' => 'bg-danger-subtle text-danger border-danger-subtle',
                                    'Manager' => 'bg-primary-subtle text-primary border-primary-subtle',
                                    'Accountant' => 'bg-success-subtle text-success border-success-subtle',
                                    default => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                };
                            @endphp
                            <span class="badge border {{ $roleClass }} px-2 py-1" style="font-size: 11px; font-weight: 600;">{{ $user->role }}</span>
                        </td>
                        <td style="font-size: 12.5px;">{{ $user->phone ?: '-' }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center gap-1.5" style="font-size: 11px; font-weight: 600; padding: 3px 8px;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #22c55e;"></span> Active
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle d-inline-flex align-items-center gap-1.5" style="font-size: 11px; font-weight: 600; padding: 3px 8px;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #94a3b8;"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size: 12px;">{{ $user->created_at?->format('M d, Y') }}</td>
                        <td class="text-end pe-4 text-nowrap">
                            <div class="action-btns-wrapper justify-content-end">
                                <a href="{{ route('users.edit', $user) }}" class="btn-action btn-action-edit" title="Edit User">
                                    <i class="bi bi-pencil"></i> <span>Edit</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            <div class="fw-semibold">No users found</div>
                            <small class="text-muted">Try adjusting your search or role filter criteria.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users</span>
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
