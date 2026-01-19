@extends('layouts.master')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Payment Details</h2>
                    <p class="text-muted mb-0">Payment #{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('Dashboard.payments.edit', $payment) }}" class="btn btn-outline-primary">
                        <i class="ri-edit-line me-2"></i>Edit Payment
                    </a>
                    <a href="{{ route('Dashboard.payments.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to Payments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Payment ID</small>
                            <div class="fw-bold">#{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Status</small>
                            <div>
                                <span class="badge {{ $payment->status_badge }}">{{ $payment->status_text }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Amount</small>
                            <div class="fw-bold text-success fs-4">Rs{{ number_format($payment->amount, 0) }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Payment Date</small>
                            <div class="fw-medium">{{ $payment->payment_date->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Payment Type</small>
                            <div>
                                <span class="badge bg-light text-dark">{{ $payment->payment_type_text }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Payment Method</small>
                            <div>
                                <span class="badge bg-secondary">{{ $payment->payment_method_text }}</span>
                            </div>
                        </div>
                        @if($payment->reference_number)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Reference Number</small>
                                <div class="fw-medium">{{ $payment->reference_number }}</div>
                            </div>
                        @endif
                        @if($payment->notes)
                            <div class="col-12 mb-3">
                                <small class="text-muted">Notes</small>
                                <div class="fw-medium">{{ $payment->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Booking -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Related Booking</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Booking ID</small>
                            <div>
                                <a href="{{ route('Dashboard.bookings.show', $payment->booking) }}" 
                                   class="fw-bold text-decoration-none">
                                    #{{ str_pad($payment->booking->id, 6, '0', STR_PAD_LEFT) }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Booking Status</small>
                            <div>
                                <span class="badge {{ $payment->booking->status_badge }}">
                                    {{ $payment->booking->status_text }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Booking Date</small>
                            <div class="fw-medium">{{ $payment->booking->booking_date->format('M d, Y') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Delivery Date</small>
                            <div class="fw-medium">{{ $payment->booking->delivery_date->format('M d, Y') }}</div>
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="mt-3">
                        <small class="text-muted">Services</small>
                        <div class="mt-2">
                            @foreach($payment->booking->bookingItems as $item)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-scissors-line text-primary me-2"></i>
                                    <span class="fw-medium">{{ $item->service->name }}</span>
                                    @if($item->quantity > 1)
                                        <span class="badge bg-secondary ms-2">{{ $item->quantity }}</span>
                                    @endif
                                    <span class="ms-auto text-success">Rs{{ number_format($item->total_price, 0) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Payment Summary -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="ri-user-line fs-4"></i>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <h5 class="mb-1">{{ $payment->customer->name }}</h5>
                        <p class="text-muted mb-0">{{ $payment->customer->phone }}</p>
                    </div>
                    @if($payment->customer->address)
                        <div class="mb-3">
                            <small class="text-muted">Address</small>
                            <div>{{ $payment->customer->address }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Summary -->
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

                    <div class="mb-3">
                        <small class="text-muted">Payment Status</small>
                        <div>
                            <span class="badge {{ $payment->booking->payment_status_badge }}">
                                {{ $payment->booking->payment_status_text }}
                            </span>
                        </div>
                    </div>

                    @if($payment->booking->remaining_amount > 0)
                        <div class="alert alert-warning">
                            <i class="ri-alert-line me-2"></i>
                            <small>Rs{{ number_format($payment->booking->remaining_amount, 0) }} payment is still pending.</small>
                        </div>
                        <a href="{{ route('Dashboard.payments.create', ['booking_id' => $payment->booking->id]) }}" 
                           class="btn btn-primary btn-sm w-100">
                            <i class="ri-add-line me-2"></i>Add Another Payment
                        </a>
                    @else
                        <div class="alert alert-success">
                            <i class="ri-check-line me-2"></i>
                            <small>Payment completed for this booking!</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection