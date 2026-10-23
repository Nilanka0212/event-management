@extends('layouts.app')
@section('title', 'Edit Customer - ' . $customer->name)
@section('page-title', 'Edit Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        
        <!-- Simple Page Header -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Edit Customer</h4>
                <p class="text-muted small mb-0">Update customer details below.</p>
            </div>
            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 mb-3">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Simple, Clean Card -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('customers.update', $customer) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Full Name -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-dark mb-1">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name', $customer->name) }}" placeholder="Customer Name" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Phone -->
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                value="{{ old('phone', $customer->phone) }}" placeholder="077 123 4567" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Alternate Phone -->
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">
                                Alternate Phone / WhatsApp
                            </label>
                            <input type="text" name="phone2" class="form-control @error('phone2') is-invalid @enderror" 
                                value="{{ old('phone2', $customer->phone2) }}" placeholder="071 987 6543">
                            @error('phone2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Email -->
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">
                                Email Address
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email', $customer->email) }}" placeholder="email@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- NIC -->
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">
                                NIC / ID Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nic" class="form-control @error('nic') is-invalid @enderror" 
                                value="{{ old('nic', $customer->nic) }}" placeholder="199012345678" required>
                            @error('nic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-dark mb-1">
                                Address
                            </label>
                            <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" 
                                placeholder="Customer Address">{{ old('address', $customer->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-dark mb-1">
                                Notes
                            </label>
                            <textarea name="notes" rows="2" class="form-control @error('notes') is-invalid @enderror" 
                                placeholder="Any additional notes or preferences">{{ old('notes', $customer->notes) }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Active Status Switch -->
                        <div class="col-12">
                            <div class="form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" 
                                    {{ old('is_active', $customer->is_active) ? 'checked' : '' }} style="cursor: pointer;">
                                <label class="form-check-label small fw-semibold" for="isActive">
                                    Active Customer
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="d-flex justify-content-end gap-2 pt-3 mt-4 border-top">
                        <a href="{{ route('customers.index') }}" class="btn btn-light border px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-check2 me-1"></i> Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection