@extends('layouts.master')

@section('css')
<style>
    .booking-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
    }
    .info-card {
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-2px);
    }
    .invoice-section {
        background: #f8f9fa;
        border-radius: 10px;
        border: 2px dashed #dee2e6;
    }
    .print-invoice {
        background: white;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
    @media print {
        .no-print { display: none !important; }
        .print-invoice { box-shadow: none; }
        body { background: white; }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <!-- Header -->
    <div class="booking-header p-4 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center mb-2">
                    <h4 class="mb-0 me-3">Booking Details</h4>
                    <span class="badge {{ $booking->status_badge }} fs-6">
                        {{ $booking->status_text }}
                    </span>
                </div>
                <p class="mb-0 opacity-75">
                    Booking ID: #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }} • 
                    Created: {{ $booking->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="col-auto no-print">
                <div class="d-flex gap-2">
                    <a href="{{ route('Dashboard.bookings.invoice', $booking) }}" class="btn btn-success" target="_blank">
                        <i class="ri-file-pdf-line me-1"></i>Download Invoice
                    </a>
                    <button class="btn btn-light" onclick="window.print()">
                        <i class="ri-printer-line me-1"></i>Print Invoice
                    </button>
                    <a href="{{ route('Dashboard.bookings.edit', $booking) }}" class="btn btn-warning">
                        <i class="ri-pencil-line me-1"></i>Edit
                    </a>
                    <a href="{{ route('Dashboard.bookings.index') }}" class="btn btn-outline-light">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Booking Info -->
        <div class="col-xl-8">
            <!-- Customer Information -->
            <div class="card info-card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="ri-user-line me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Name</h6>
                            <p class="mb-3">{{ $booking->customer->name }}</p>
                            
                            <h6 class="text-muted mb-1">Phone</h6>
                            <p class="mb-3">
                                <a href="tel:{{ $booking->customer->phone }}" class="text-decoration-none">
                                    {{ $booking->customer->phone }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Gender</h6>
                            <p class="mb-3">{{ ucfirst($booking->customer->gender) }}</p>
                            
                            <h6 class="text-muted mb-1">Address</h6>
                            <p class="mb-3">{{ $booking->customer->address ?: 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service & Design Information -->
            <div class="card info-card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="ri-scissors-line me-2"></i>Service & Design Details
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Services List -->
                    @foreach($booking->bookingItems as $index => $item)
                        <div class="row {{ $index > 0 ? 'border-top pt-3 mt-3' : '' }}">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Service {{ $index + 1 }}</h6>
                                <p class="mb-2">{{ $item->service->name }}</p>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Quantity</small>
                                        <strong class="text-primary">{{ $item->quantity }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Unit Price</small>
                                        <strong>Rs{{ number_format($item->unit_price, 0) }}</strong>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Total Price</small>
                                    <strong class="text-success">Rs{{ number_format($item->total_price, 0) }}</strong>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    {{ ucfirst($item->service->gender) }} • 
                                    {{ ucfirst($item->service->service_category) }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                @if($item->designCatalog)
                                    <h6 class="text-muted mb-1">Design</h6>
                                    <p class="mb-2">{{ $item->designCatalog->title }}</p>
                                    @if($item->designCatalog->description)
                                        <small class="text-muted d-block">{{ $item->designCatalog->description }}</small>
                                    @endif
                                    @if($item->designCatalog->price_adjustment > 0)
                                        <small class="text-success d-block">+Rs{{ number_format($item->designCatalog->price_adjustment, 0) }} design fee</small>
                                    @endif
                                    @if($item->designCatalog->image_path)
                                        <div class="mt-2">
                                            <img src="{{ asset($item->designCatalog->image_path) }}" 
                                                 alt="Design" class="img-thumbnail" style="max-width: 100px;">
                                        </div>
                                    @endif
                                    <div class="mt-2">
                                        <small class="text-info">Applied to all {{ $item->quantity }} piece(s)</small>
                                    </div>
                                @else
                                    <h6 class="text-muted mb-1">Design</h6>
                                    <p class="text-muted">No design selected</p>
                                    <small class="text-muted">{{ $item->quantity }} piece(s) without design</small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Schedule Information -->
            <div class="card info-card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="ri-calendar-line me-2"></i>Schedule & Priority
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-muted mb-1">Booking Date</h6>
                            <p class="mb-0">{{ $booking->booking_date->format('M d, Y') }}</p>
                            <small class="text-muted">{{ $booking->booking_date->diffForHumans() }}</small>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-1">Delivery Date</h6>
                            <p class="mb-0">{{ $booking->delivery_date->format('M d, Y') }}</p>
                            <small class="text-muted">{{ $booking->delivery_date->diffForHumans() }}</small>
                        </div>
                        <div class="col-md-4">
                            @if($booking->priority)
                                <h6 class="text-muted mb-1">Priority</h6>
                                <span class="badge bg-{{ $booking->priority == 'high' ? 'danger' : ($booking->priority == 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($booking->priority) }} Priority
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($booking->notes)
                        <hr>
                        <h6 class="text-muted mb-1">Notes</h6>
                        <p class="mb-0">{{ $booking->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Payment -->
        <div class="col-xl-4">
            <!-- Payment Information -->
            <div class="card info-card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="ri-money-rupee-circle-line me-2"></i>Payment Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Amount:</span>
                        <strong class="text-success">Rs{{ number_format($booking->total_amount, 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Advance Paid:</span>
                        <span>Rs{{ number_format($booking->advance_amount, 0) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Remaining:</strong>
                        <strong class="{{ $booking->remaining_amount > 0 ? 'text-warning' : 'text-success' }}">
                            Rs{{ number_format($booking->remaining_amount, 0) }}
                        </strong>
                    </div>
                    
                    @if($booking->remaining_amount > 0)
                        <div class="mt-3">
                            <div class="alert alert-warning">
                                <small>
                                    <i class="ri-information-line me-1"></i>
                                    Payment pending: Rs{{ number_format($booking->remaining_amount, 0) }}
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="mt-3">
                            <div class="alert alert-success">
                                <small>
                                    <i class="ri-check-line me-1"></i>
                                    Payment completed
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card info-card no-print">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-settings-line me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <select class="form-select" onchange="updateStatus(this.value)">
                            <option value="">Change Status</option>
                            @foreach(\App\Models\Booking::getStatuses() as $key => $status)
                                <option value="{{ $key }}" {{ $booking->status == $key ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="ri-printer-line me-1"></i>Print Invoice
                        </button>
                        
                        <a href="tel:{{ $booking->customer->phone }}" class="btn btn-outline-success">
                            <i class="ri-phone-line me-1"></i>Call Customer
                        </a>
                        
                        <button class="btn btn-outline-danger" onclick="deleteBooking()">
                            <i class="ri-delete-bin-line me-1"></i>Delete Booking
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Section (Print Only) -->
    <div class="invoice-section p-4 mt-4 print-invoice">
        <div class="text-center mb-4">
            <h2 class="mb-1">INVOICE</h2>
            <p class="text-muted">Booking Invoice #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted">BILL TO:</h6>
                <p class="mb-1"><strong>{{ $booking->customer->name }}</strong></p>
                <p class="mb-1">{{ $booking->customer->phone }}</p>
                @if($booking->customer->address)
                    <p class="mb-0">{{ $booking->customer->address }}</p>
                @endif
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-1"><strong>Invoice Date:</strong> {{ $booking->created_at->format('M d, Y') }}</p>
                <p class="mb-1"><strong>Booking Date:</strong> {{ $booking->booking_date->format('M d, Y') }}</p>
                <p class="mb-0"><strong>Delivery Date:</strong> {{ $booking->delivery_date->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Service</th>
                        <th>Design</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->bookingItems as $item)
                        <tr>
                            <td>
                                {{ $item->service->name }}
                                <br><small class="text-muted">{{ ucfirst($item->service->gender) }}</small>
                            </td>
                            <td>
                                {{ $item->designCatalog->title ?? 'No Design' }}
                                @if($item->designCatalog && $item->designCatalog->price_adjustment > 0)
                                    <br><small class="text-success">+Rs{{ number_format($item->designCatalog->price_adjustment, 0) }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <strong>{{ $item->quantity }}</strong>
                            </td>
                            <td class="text-end">Rs{{ number_format($item->unit_price, 0) }}</td>
                            <td class="text-end">
                                <strong>Rs{{ number_format($item->total_price, 0) }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total Amount:</th>
                        <th class="text-end">Rs{{ number_format($booking->total_amount, 0) }}</th>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end">Advance Paid:</td>
                        <td class="text-end">Rs{{ number_format($booking->advance_amount, 0) }}</td>
                    </tr>
                    <tr class="table-warning">
                        <th colspan="3" class="text-end">Balance Due:</th>
                        <th class="text-end">Rs{{ number_format($booking->remaining_amount, 0) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($booking->notes)
            <div class="mb-3">
                <h6>Notes:</h6>
                <p>{{ $booking->notes }}</p>
            </div>
        @endif

        <div class="text-center mt-4">
            <p class="text-muted small">Thank you for your business!</p>
        </div>
    </div>

    <!-- Payment History Section (Screen Only) -->
    <div class="no-print mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-money-dollar-circle-line me-2"></i>Payment History
                </h5>
                @if($booking->remaining_amount > 0)
                    <a href="{{ route('Dashboard.payments.create', ['booking_id' => $booking->id]) }}" 
                       class="btn btn-primary btn-sm">
                        <i class="ri-add-line me-2"></i>Add Payment
                    </a>
                @endif
            </div>
            <div class="card-body">
                <!-- Payment Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-1">Rs{{ number_format($booking->total_amount, 0) }}</h4>
                            <small class="text-muted">Total Amount</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <h4 class="mb-1 text-success">Rs{{ number_format($booking->total_paid, 0) }}</h4>
                            <small class="text-muted">Total Paid</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                            <h4 class="mb-1 text-danger">Rs{{ number_format($booking->remaining_amount, 0) }}</h4>
                            <small class="text-muted">Remaining</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <span class="badge {{ $booking->payment_status_badge }} fs-6">
                                {{ $booking->payment_status_text }}
                            </span>
                            <br>
                            <small class="text-muted">Payment Status</small>
                        </div>
                    </div>
                </div>

                <!-- Payment Transactions -->
                @if($booking->payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Method</th>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->payments as $payment)
                                    <tr>
                                        <td>
                                            <span class="fw-medium">#{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">Rs{{ number_format($payment->amount, 0) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $payment->payment_type_text }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $payment->payment_method_text }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $payment->payment_date->format('M d, Y') }}</span>
                                            <br>
                                            <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            {{ $payment->reference_number ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $payment->status_badge }}">
                                                {{ $payment->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                        type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('Dashboard.payments.show', $payment) }}">
                                                            <i class="ri-eye-line me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('Dashboard.payments.edit', $payment) }}">
                                                            <i class="ri-edit-line me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ri-money-dollar-circle-line display-4 text-muted mb-3"></i>
                        <h5 class="text-muted">No payments recorded</h5>
                        <p class="text-muted">Add the first payment for this booking</p>
                        <a href="{{ route('Dashboard.payments.create', ['booking_id' => $booking->id]) }}" 
                           class="btn btn-primary">
                            <i class="ri-add-line me-2"></i>Add Payment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this booking? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('Dashboard.bookings.destroy', $booking) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function updateStatus(status) {
    if (!status) return;
    
    fetch(`{{ route('Dashboard.bookings.updateStatus', $booking) }}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status');
    });
}

function deleteBooking() {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection