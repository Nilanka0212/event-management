@extends('layouts.app')
@section('title', 'Add System User')
@section('page-title', 'Add System User')

@section('content')
<div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Register New User</h5>
                    <small class="text-muted">Create system account credentials and assign role permissions.</small>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Users
                </a>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger py-2 mb-4">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="e.g. johndoe" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="user@eventpro.com" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">System Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="Staff" {{ old('role') === 'Staff' ? 'selected' : '' }}>Staff</option>
                                <option value="Manager" {{ old('role') === 'Manager' ? 'selected' : '' }}>Manager</option>
                                <option value="Accountant" {{ old('role') === 'Accountant' ? 'selected' : '' }}>Accountant</option>
                                <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+94 77 123 4567">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type password" required>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Account is Active</label>
                        <div class="text-muted small">Inactive users cannot log in to the system.</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('users.index') }}" class="btn btn-cancel px-3">Cancel</a>
                        <button type="submit" class="btn btn-primary px-3">
                            <i class="bi bi-check-lg me-1"></i> Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>
@endsection
