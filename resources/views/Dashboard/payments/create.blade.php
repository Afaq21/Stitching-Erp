@extends('layouts.master')

@section('title', 'Add Payment')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Add Payment</h2>
                    <p class="text-muted mb-0">Record a new payment for booking</p>
                </div>
                <a href="{{ route('Dashboard.payments.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-2"></i>Back to Payments
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('Dashboard.payments.store') }}" method="POST" id="paymentForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Booking Selection -->
                            <div class="col-md-6 mb-3">
                                <label for="booking_id" class="form-label">Select Booking <span class="text-danger">*</span></label>
                                <select class="form-select @error('booking_id') is-invalid @enderror" 
                                        id="booking_id" name="booking_id" required>
                                    <option value="">Choose booking...</option>
                                    @foreach($bookings as $bookingOption)
                                        <option value="{{ $bookingOption->id }}" 
                                                data-customer="{{ $bookingOption->customer->name }}"
                                                data-total="{{ $bookingOption->total_amount }}"
                                                data-paid="{{ $bookingOption->total_paid }}"
                                                data-remaining="{{ $bookingOption->remaining_amount }}"
                                                {{ (old('booking_id', $booking?->id) == $bookingOption->id) ? 'selected' : '' }}>
                                            #{{ str_pad($bookingOption->id, 6, '0', STR_PAD_LEFT) }} - {{ $bookingOption->customer->name }}
                                            (Remaining: Rs{{ number_format($bookingOption->remaining_amount, 0) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Amount -->
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs</span>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" 
                                           min="0.01" step="0.01" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="remainingAmount"></div>
                            </div>

                            <!-- Payment Type -->
                            <div class="col-md-6 mb-3">
                                <label for="payment_type" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_type') is-invalid @enderror" 
                                        id="payment_type" name="payment_type" required>
                                    <option value="">Choose type...</option>
                                    @foreach($paymentTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('payment_type') == $key ? 'selected' : '' }}>
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
                                    <option value="">Choose method...</option>
                                    @foreach($paymentMethods as $key => $method)
                                        <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>
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
                                       value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reference Number -->
                            <div class="col-md-6 mb-3">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                       id="reference_number" name="reference_number" 
                                       value="{{ old('reference_number') }}" 
                                       placeholder="Cheque no, Transaction ID, etc.">
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Additional notes about this payment...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('Dashboard.payments.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>Add Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="col-lg-4">
            <div class="card" id="bookingSummary" style="display: none;">
                <div class="card-header">
                    <h5 class="card-title mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Customer</small>
                        <div class="fw-medium" id="customerName">-</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Booking ID</small>
                        <div class="fw-medium" id="bookingId">-</div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Amount:</span>
                        <span class="fw-bold" id="totalAmount">Rs0</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Already Paid:</span>
                        <span class="text-success" id="paidAmount">Rs0</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Remaining:</span>
                        <span class="fw-bold text-danger" id="remainingAmountDisplay">Rs0</span>
                    </div>

                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <small>Payment amount cannot exceed the remaining amount.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingSelect = document.getElementById('booking_id');
    const amountInput = document.getElementById('amount');
    const bookingSummary = document.getElementById('bookingSummary');
    
    bookingSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const customer = selectedOption.dataset.customer;
            const total = parseFloat(selectedOption.dataset.total);
            const paid = parseFloat(selectedOption.dataset.paid);
            const remaining = parseFloat(selectedOption.dataset.remaining);
            
            // Update summary
            document.getElementById('customerName').textContent = customer;
            document.getElementById('bookingId').textContent = '#' + selectedOption.value.padStart(6, '0');
            document.getElementById('totalAmount').textContent = 'Rs' + total.toLocaleString();
            document.getElementById('paidAmount').textContent = 'Rs' + paid.toLocaleString();
            document.getElementById('remainingAmountDisplay').textContent = 'Rs' + remaining.toLocaleString();
            
            // Update amount input
            amountInput.max = remaining;
            amountInput.placeholder = 'Max: Rs' + remaining.toLocaleString();
            
            // Update remaining amount text
            document.getElementById('remainingAmount').textContent = 'Remaining amount: Rs' + remaining.toLocaleString();
            
            bookingSummary.style.display = 'block';
        } else {
            bookingSummary.style.display = 'none';
            amountInput.max = '';
            amountInput.placeholder = '';
            document.getElementById('remainingAmount').textContent = '';
        }
    });

    // Trigger change event if booking is pre-selected
    if (bookingSelect.value) {
        bookingSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection