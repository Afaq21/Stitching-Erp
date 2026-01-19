@extends('layouts.master')

@section('css')
<style>
    .booking-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    /* Custom Pagination Styling */
    .pagination-rounded .page-link {
        border-radius: 50px;
        margin: 0 2px;
        border: 1px solid #e9ecef;
        color: #6c757d;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }
    .pagination-rounded .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .pagination-rounded .page-link:hover {
        background-color: #f8f9fa;
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-1px);
    }
    .pagination-rounded .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #adb5bd;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
        border-radius: 50px;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
    }
    .stats-card.pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .stats-card.confirmed {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stats-card.in-progress {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    .stats-card.ready {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .priority-indicator {
        width: 4px;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        border-radius: 0 0 0 8px;
    }
    .priority-high { background: #ff4757; }
    .priority-medium { background: #ffa502; }
    .priority-low { background: #2ed573; }
    .search-box {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <!-- Header Section with Gradient Background -->
    <div class="bg-gradient-primary text-white p-4 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="mb-1 text-white">
                    <i class="ri-calendar-check-line me-2"></i>Bookings Management
                </h4>
                <p class="mb-0 opacity-75">Manage all your customer bookings efficiently</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('Dashboard.bookings.create') }}" class="btn btn-light btn-lg">
                    <i class="ri-add-line me-1"></i>New Booking
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card stats-card text-center p-3">
                <div class="card-body p-0">
                    <h3 class="mb-1">{{ $stats['total'] }}</h3>
                    <p class="mb-0 opacity-75">Total Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card stats-card pending text-center p-3">
                <div class="card-body p-0">
                    <h3 class="mb-1">{{ $stats['pending'] }}</h3>
                    <p class="mb-0 opacity-75">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card stats-card confirmed text-center p-3">
                <div class="card-body p-0">
                    <h3 class="mb-1">{{ $stats['confirmed'] }}</h3>
                    <p class="mb-0 opacity-75">Confirmed</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card stats-card in-progress text-center p-3">
                <div class="card-body p-0">
                    <h3 class="mb-1">{{ $stats['in_progress'] }}</h3>
                    <p class="mb-0 opacity-75">In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card stats-card ready text-center p-3">
                <div class="card-body p-0">
                    <h3 class="mb-1">{{ $stats['ready'] }}</h3>
                    <p class="mb-0 opacity-75">Ready</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 text-center p-3" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body p-0">
                    <h3 class="mb-1 text-dark">{{ $bookings->total() }}</h3>
                    <p class="mb-0 text-muted">This Month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="position-relative">
                        <i class="ri-search-line position-absolute" style="left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                        <input type="text" class="form-control" id="searchBookings" 
                               placeholder="Search by customer name or phone..." 
                               style="padding-left: 2.5rem;"
                               value="{{ request('search') }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex gap-2">
                <a href="{{ route('Dashboard.bookings.today') }}" class="btn btn-outline-primary w-100">
                    <i class="ri-calendar-today-line me-1"></i>Today's Bookings
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Bookings Grid -->
    <div class="row" id="bookingsContainer">
        @forelse($bookings as $booking)
            <div class="col-xl-4 col-lg-6 mb-4 booking-item"
                data-status="{{ $booking->status }}"
                data-customer="{{ strtolower($booking->customer->name) }}"
                data-date="{{ $booking->booking_date->format('Y-m-d') }}">
                <div class="card booking-card h-100 position-relative">
                    <!-- Priority Indicator -->
                    @if($booking->priority)
                        <div class="priority-indicator priority-{{ $booking->priority }}"></div>
                    @endif

                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $booking->customer->name }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="ri-phone-line me-1"></i>{{ $booking->customer->phone }}
                                </p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-action btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('Dashboard.bookings.show', $booking) }}">
                                            <i class="ri-eye-line me-2 text-primary"></i>View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); editBooking({{ $booking->id }})">
                                            <i class="ri-pencil-line me-2 text-success"></i>Edit Booking
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('Dashboard.bookings.invoice', $booking) }}" target="_blank">
                                            <i class="ri-file-pdf-line me-2 text-danger"></i>Download Invoice
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); deleteBooking({{ $booking->id }})">
                                            <i class="ri-delete-bin-line me-2"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Services Info -->
                        <div class="mb-3">
                            @foreach($booking->bookingItems as $item)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-scissors-line text-primary me-2"></i>
                                    <span class="fw-medium">{{ $item->service->name }}</span>
                                    @if($item->quantity > 1)
                                        <span class="badge bg-secondary ms-2">{{ $item->quantity }}</span>
                                    @endif
                                </div>
                                @if($item->designCatalog)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ri-palette-line text-info me-2"></i>
                                        <span class="small">{{ $item->designCatalog->title }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Dates -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <small class="text-muted d-block">Booking</small>
                                    <strong>{{ $booking->booking_date->format('M d') }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <small class="text-muted d-block">Delivery</small>
                                    <strong>{{ $booking->delivery_date->format('M d') }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Total Amount:</span>
                                <strong class="text-success">Rs{{ number_format($booking->total_amount, 0) }}</strong>
                            </div>
                            @if($booking->remaining_amount > 0)
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Remaining:</span>
                                    <span class="small text-warning">Rs{{ number_format($booking->remaining_amount, 0) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="d-flex justify-content-between align-items-center">
                            <select class="form-select form-select-sm status-badge {{ $booking->status_badge }} text-white border-0"
                                    style="width: auto;"
                                    onchange="updateStatus({{ $booking->id }}, this.value)">
                                @foreach(\App\Models\Booking::getStatuses() as $key => $status)
                                    <option value="{{ $key }}" {{ $booking->status == $key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>

                            @if($booking->priority)
                                <span class="badge bg-{{ $booking->priority == 'high' ? 'danger' : ($booking->priority == 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($booking->priority) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ri-calendar-check-line display-4 text-muted"></i>
                    </div>
                    <h5 class="text-muted">No bookings found</h5>
                    <p class="text-muted">Create your first booking to get started</p>
                    <a href="{{ route('Dashboard.bookings.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Create Booking
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="d-flex justify-content-center mt-4" id="paginationContainer">
            <nav aria-label="Bookings pagination">
                <ul class="pagination pagination-rounded">
                    {{-- Previous Page Link --}}
                    @if ($bookings->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="ri-arrow-left-line"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $bookings->previousPageUrl() }}" rel="prev">
                                <i class="ri-arrow-left-line"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                        @if ($page == $bookings->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($bookings->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $bookings->nextPageUrl() }}" rel="next">
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="ri-arrow-right-line"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif
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
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Booking Modal -->
<div class="modal fade" id="editBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBookingForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Customer Selection -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer</label>
                            <select name="customer_id" id="editCustomerId" class="form-select" required>
                                <option value="">Select Customer</option>
                                @if(isset($customers))
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Service Selection -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Service</label>
                            <select name="service_id" id="editServiceId" class="form-select" required>
                                <option value="">Select Service</option>
                                @if(isset($services))
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                            {{ $service->name }} ({{ ucfirst($service->gender) }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Design Selection -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Design (Optional)</label>
                            <select name="design_catalog_id" id="editDesignId" class="form-select">
                                <option value="">No Design</option>
                                @if(isset($designs))
                                    @foreach($designs as $design)
                                        <option value="{{ $design->id }}" data-service="{{ $design->service_id }}">
                                            {{ $design->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="editStatus" class="form-select" required>
                                @foreach(\App\Models\Booking::getStatuses() as $key => $status)
                                    <option value="{{ $key }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Booking Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Booking Date</label>
                            <input type="date" name="booking_date" id="editBookingDate" class="form-control" required>
                        </div>

                        <!-- Delivery Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Delivery Date</label>
                            <input type="date" name="delivery_date" id="editDeliveryDate" class="form-control" required>
                        </div>

                        <!-- Priority -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" id="editPriority" class="form-select">
                                <option value="">Normal Priority</option>
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>

                        <!-- Total Amount -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rs</span>
                                <input type="number" name="total_amount" id="editTotalAmount" class="form-control" min="0" step="0.01" required>
                            </div>
                        </div>

                        <!-- Advance Amount -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Advance Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rs</span>
                                <input type="number" name="advance_amount" id="editAdvanceAmount" class="form-control" min="0" step="0.01">
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="editNotes" class="form-control" rows="3" placeholder="Any special instructions..."></textarea>
                        </div>

                        <!-- Payment Summary -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="mb-2">Payment Summary</h6>
                                <div class="d-flex justify-content-between">
                                    <span>Remaining Amount:</span>
                                    <strong id="editRemainingAmount">Rs</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Update Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
// Search Functionality with AJAX (same as customers module)
let searchTimeout;
document.getElementById('searchBookings').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadBookings(e.target.value);
    }, 500);
});

// Load bookings via AJAX
function loadBookings(search = '') {
    const url = new URL(window.location.href);
    if (search) {
        url.searchParams.set('search', search);
    } else {
        url.searchParams.delete('search');
    }

    fetch(url.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newGrid = doc.querySelector('#bookingsContainer');
        const newPagination = doc.querySelector('#paginationContainer');

        if (newGrid) {
            document.getElementById('bookingsContainer').innerHTML = newGrid.innerHTML;
        }
        if (newPagination) {
            document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error loading bookings:', error);
    });
}

// Handle pagination clicks
document.addEventListener('click', function(e) {
    if (e.target.closest('.pagination a')) {
        e.preventDefault();
        const url = e.target.closest('.pagination a').getAttribute('href');
        if (url) {
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGrid = doc.querySelector('#bookingsContainer');
                const newPagination = doc.querySelector('#paginationContainer');

                if (newGrid) {
                    document.getElementById('bookingsContainer').innerHTML = newGrid.innerHTML;
                }
                if (newPagination) {
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                }
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(error => {
                console.error('Error loading page:', error);
            });
        }
    }
});

// Update status
function updateStatus(bookingId, status) {
    fetch(`/Dashboard/bookings/${bookingId}/status`, {
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

// Delete booking
function deleteBooking(id) {
    document.getElementById('deleteForm').action = `/Dashboard/bookings/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Edit booking
function editBooking(id) {
    window.location.href = `/Dashboard/bookings/${id}/edit`;
}
</script>
@endsection
