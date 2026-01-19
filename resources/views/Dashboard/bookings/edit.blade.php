@extends('layouts.master')

@section('title', 'Edit Booking')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Edit Booking</h2>
                    <p class="text-muted mb-0">Booking #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('Dashboard.bookings.show', $booking) }}" class="btn btn-outline-info">
                        <i class="ri-eye-line me-2"></i>View Details
                    </a>
                    <a href="{{ route('Dashboard.bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to Bookings
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
                    <h5 class="card-title mb-0">Booking Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('Dashboard.bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Customer Selection -->
                            <div class="col-md-6 mb-3">
                                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                <select class="form-select @error('customer_id') is-invalid @enderror" 
                                        id="customer_id" name="customer_id" required>
                                    <option value="">Select Customer...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                                {{ old('customer_id', $booking->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    @foreach($statuses as $key => $statusText)
                                        <option value="{{ $key }}" 
                                                {{ old('status', $booking->status) == $key ? 'selected' : '' }}>
                                            {{ $statusText }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Booking Date -->
                            <div class="col-md-6 mb-3">
                                <label for="booking_date" class="form-label">Booking Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('booking_date') is-invalid @enderror" 
                                       id="booking_date" name="booking_date" 
                                       value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" required>
                                @error('booking_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Delivery Date -->
                            <div class="col-md-6 mb-3">
                                <label for="delivery_date" class="form-label">Delivery Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                       id="delivery_date" name="delivery_date" 
                                       value="{{ old('delivery_date', $booking->delivery_date->format('Y-m-d')) }}" required>
                                @error('delivery_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Total Amount -->
                            <div class="col-md-6 mb-3">
                                <label for="total_amount" class="form-label">Total Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs</span>
                                    <input type="number" class="form-control @error('total_amount') is-invalid @enderror" 
                                           id="total_amount" name="total_amount" 
                                           value="{{ old('total_amount', $booking->total_amount) }}" 
                                           min="0" step="0.01" required>
                                </div>
                                @error('total_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                        id="priority" name="priority">
                                    <option value="">Select Priority...</option>
                                    <option value="low" {{ old('priority', $booking->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', $booking->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority', $booking->priority) == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="4" 
                                          placeholder="Additional notes about this booking...">{{ old('notes', $booking->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('Dashboard.bookings.show', $booking) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Booking Info -->
        <div class="col-lg-4">
            <!-- Current Booking Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Booking Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Booking ID</small>
                        <div class="fw-bold">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Customer</small>
                        <div class="fw-medium">{{ $booking->customer->name }}</div>
                        <small class="text-muted">{{ $booking->customer->phone }}</small>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Current Status</small>
                        <div>
                            <span class="badge {{ $booking->status_badge }}">{{ $booking->status_text }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Current Amount</small>
                        <div class="fw-bold text-success fs-4">Rs{{ number_format($booking->total_amount, 0) }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Created</small>
                        <div>{{ $booking->created_at->format('M d, Y h:i A') }}</div>
                    </div>

                    @if($booking->updated_at != $booking->created_at)
                        <div class="mb-3">
                            <small class="text-muted">Last Updated</small>
                            <div>{{ $booking->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Services Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Services in this Booking</h5>
                </div>
                <div class="card-body">
                    @if($booking->bookingItems->count() > 0)
                        @foreach($booking->bookingItems as $item)
                            <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                                <i class="ri-scissors-line text-primary me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">{{ $item->service->name }}</div>
                                    @if($item->designCatalog)
                                        <small class="text-muted">{{ $item->designCatalog->title }}</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">Rs{{ number_format($item->total_price, 0) }}</div>
                                    @if($item->quantity > 1)
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No services found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection