@extends('layouts.master')

@section('title', 'Edit Payment')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Edit Payment</h2>
                    <p class="text-muted mb-0">Payment #{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('Dashboard.payments.show', $payment) }}" class="btn btn-outline-info">
                        <i class="ri-eye-line me-2"></i>View Details
                    </a>
                    <a href="{{ route('Dashboard.payments.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to Payments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Edit Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('Dashboard.payments.update', $payment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Payment Amount -->
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs</span>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount', $payment->amount) }}" 
                                           min="0.01" step="0.01" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Type -->
                            <div class="col-md-6 mb-3">
                                <label for="payment_type" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_type') is-invalid @enderror" 
                                        id="payment_type" name="payment_type" required>
                                    @foreach($paymentTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('payment_type', $payment->payment_type) == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    @foreach($paymentMethods as $key => $method)
                                        <option value="{{ $key }}" {{ old('payment_method', $payment->payment_method) == $key ? 'selected' : '' }}>
                                            {{ $method }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Date -->
                            <div class="col-md-6 mb-3">
                                <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                       id="payment_date" name="payment_date" 
                                       value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reference Number -->
                            <div class="col-md-6 mb-3">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                       id="reference_number" name="reference_number" 
                                       value="{{ old('reference_number', $payment->reference_number) }}" 
                                       placeholder="Cheque no, Transaction ID, etc.">
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    @foreach($statuses as $key => $statusText)
                                        <option value="{{ $key }}" {{ old('status', $payment->status) == $key ? 'selected' : '' }}>
                                            {{ $statusText }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Additional notes about this payment...">{{ old('notes', $payment->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('Dashboard.payments.show', $payment) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>Update Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Payment Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Payment ID</small>
                        <div class="fw-bold">#{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Customer</small>
                        <div class="fw-medium">{{ $payment->customer->name }}</div>
                        <small class="text-muted">{{ $payment->customer->phone }}</small>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Booking</small>
                        <div>
                            <a href="{{ route('Dashboard.bookings.show', $payment->booking) }}" 
                               class="fw-medium text-decoration-none">
                                #{{ str_pad($payment->booking->id, 6, '0', STR_PAD_LEFT) }}
                            </a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Current Amount</small>
                        <div class="fw-bold text-success fs-4">Rs{{ number_format($payment->amount, 0) }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Current Status</small>
                        <div>
                            <span class="badge {{ $payment->status_badge }}">{{ $payment->status_text }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Created</small>
                        <div>{{ $payment->created_at->format('M d, Y h:i A') }}</div>
                    </div>

                    @if($payment->updated_at != $payment->created_at)
                        <div class="mb-3">
                            <small class="text-muted">Last Updated</small>
                            <div>{{ $payment->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Booking Payment Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Amount:</span>
                        <span class="fw-bold">Rs{{ number_format($payment->booking->total_amount, 0) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Paid:</span>
                        <span class="text-success">Rs{{ number_format($payment->booking->total_paid, 0) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Remaining:</span>
                        <span class="fw-bold text-danger">Rs{{ number_format($payment->booking->remaining_amount, 0) }}</span>
                    </div>

                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <small>Changes to this payment will affect the booking's payment status.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection