@extends('layouts.master')

@section('css')
<style>
    .today-header {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
        border-radius: 15px;
        color: #333;
    }
    .booking-timeline {
        position: relative;
    }
    .timeline-item {
        position: relative;
        padding: 1rem;
        margin-bottom: 1rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }
    .timeline-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .timeline-item.priority-high {
        border-left-color: #dc3545;
    }
    .timeline-item.priority-medium {
        border-left-color: #ffc107;
    }
    .timeline-item.priority-low {
        border-left-color: #28a745;
    }
    .time-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    .stats-mini {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
    }
    .search-container {
        position: relative;
        max-width: 400px;
    }
    .search-input {
        border-radius: 8px;
        padding-left: 2.5rem;
        border: 1px solid #dee2e6;
        background: #fff;
    }
    .search-input:focus {
        border-color: #405189;
        box-shadow: 0 0 0 0.2rem rgba(64,81,137,0.25);
    }
    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <!-- Header -->
    <div class="today-header p-4 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="mb-1">
                    <i class="ri-calendar-today-line me-2"></i>Today's Bookings
                </h4>
                <p class="mb-0 opacity-75">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="col-auto">
                <div class="stats-mini">
                    <h3 class="mb-0">{{ $bookings->total() }}</h3>
                    <small>Bookings Today</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="search-container">
                <i class="ri-search-line search-icon"></i>
                <input type="text" class="form-control search-input" id="searchInput"
                       placeholder="Search by customer name or phone..." value="{{ request('search') }}">
            </div>
        </div>
    </div>

    @if($bookings->count() > 0)
        <!-- Bookings Timeline -->
        <div class="booking-timeline" id="gridContainer">
            @foreach($bookings as $booking)
                <div class="timeline-item {{ $booking->priority ? 'priority-' . $booking->priority : '' }}"
                     data-booking-id="{{ $booking->id }}"
                     data-customer-id="{{ $booking->customer_id }}"
                     data-status="{{ $booking->status }}"
                     data-booking-date="{{ $booking->booking_date->format('Y-m-d') }}"
                     data-delivery-date="{{ $booking->delivery_date->format('Y-m-d') }}"
                     data-priority="{{ $booking->priority }}"
                     data-total-amount="{{ $booking->total_amount }}"
                     data-notes="{{ $booking->notes }}">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <h6 class="mb-0 me-3">{{ $booking->customer->name }}</h6>
                                <span class="time-badge">
                                    {{ $booking->booking_date->format('g:i A') }}
                                </span>
                                @if($booking->priority)
                                    <span class="badge bg-{{ $booking->priority == 'high' ? 'danger' : ($booking->priority == 'medium' ? 'warning' : 'success') }} ms-2">
                                        {{ ucfirst($booking->priority) }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Services</small>
                                    @foreach($booking->bookingItems as $item)
                                        <span class="d-block">{{ $item->service->name }}</span>
                                    @endforeach
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Phone</small>
                                    <a href="tel:{{ $booking->customer->phone }}" class="text-decoration-none">
                                        {{ $booking->customer->phone }}
                                    </a>
                                </div>
                            </div>
                            
                            @if($booking->bookingItems->where('designCatalog')->count() > 0)
                                <div class="mt-2">
                                    <small class="text-muted">Designs: </small>
                                    @foreach($booking->bookingItems->where('designCatalog') as $item)
                                        <span class="text-info d-block">{{ $item->designCatalog->title }}</span>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($booking->notes)
                                <div class="mt-2">
                                    <small class="text-muted">Notes: </small>
                                    <span>{{ Str::limit($booking->notes, 100) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-4 text-md-end">
                            <div class="mb-2">
                                <span class="badge {{ $booking->status_badge }} fs-6">
                                    {{ $booking->status_text }}
                                </span>
                            </div>
                            
                            <div class="mb-2">
                                <strong class="text-success">Rs{{ number_format($booking->total_amount, 0) }}</strong>
                                @if($booking->remaining_amount > 0)
                                    <br><small class="text-warning">Pending: Rs{{ number_format($booking->remaining_amount, 0) }}</small>
                                @endif
                            </div>
                            
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('Dashboard.bookings.show', $booking) }}" class="btn btn-outline-primary">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="tel:{{ $booking->customer->phone }}" class="btn btn-outline-success">
                                    <i class="ri-phone-line"></i>
                                </a>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="ri-more-line"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('Dashboard.bookings.show', $booking) }}">
                                            <i class="ri-eye-line me-2"></i>View Details
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $booking->id }}, 'confirmed')">
                                            <i class="ri-check-line me-2"></i>Mark Confirmed
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $booking->id }}, 'in_progress')">
                                            <i class="ri-play-line me-2"></i>Start Work
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $booking->id }}, 'ready')">
                                            <i class="ri-check-double-line me-2"></i>Mark Ready
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="d-flex justify-content-center mt-4" id="paginationContainer">
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="mb-4">
                <i class="ri-calendar-check-line display-1 text-muted"></i>
            </div>
            <h5 class="text-muted mb-3">No bookings for today</h5>
            <p class="text-muted mb-4">You have no scheduled bookings for today. Enjoy your free time!</p>
            <a href="{{ route('Dashboard.bookings.create') }}" class="btn btn-primary">
                <i class="ri-add-line me-1"></i>Create New Booking
            </a>
        </div>
    @endif
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
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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

                        <!-- Notes -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="editNotes" class="form-control" rows="3" placeholder="Any special instructions..."></textarea>
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
// Search with debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadData(e.target.value);
    }, 500);
});

// Load data via AJAX
function loadData(search = '') {
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
        const newGrid = doc.querySelector('#gridContainer');
        const newPagination = doc.querySelector('#paginationContainer');

        if (newGrid) {
            document.getElementById('gridContainer').innerHTML = newGrid.innerHTML;
        }
        if (newPagination) {
            const paginationContainer = document.getElementById('paginationContainer');
            if (paginationContainer) {
                paginationContainer.innerHTML = newPagination.innerHTML;
            }
        }
    })
    .catch(error => {
        console.error('Error loading data:', error);
    });
}

// Handle pagination clicks
document.addEventListener('click', function(e) {
    if (e.target.closest('.pagination a')) {
        e.preventDefault();
        const url = e.target.closest('.pagination a').getAttribute('href');
        if (url) {
            const searchValue = document.getElementById('searchInput').value;
            const urlObj = new URL(url);
            if (searchValue) {
                urlObj.searchParams.set('search', searchValue);
            }

            fetch(urlObj.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGrid = doc.querySelector('#gridContainer');
                const newPagination = doc.querySelector('#paginationContainer');

                if (newGrid) {
                    document.getElementById('gridContainer').innerHTML = newGrid.innerHTML;
                }
                if (newPagination) {
                    const paginationContainer = document.getElementById('paginationContainer');
                    if (paginationContainer) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    }
                }

                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }
});

// Test function
function testModal() {
    console.log('Test modal clicked');
    const modal = document.getElementById('editBookingModal');
    console.log('Modal element:', modal);
    
    if (modal) {
        new bootstrap.Modal(modal).show();
    } else {
        alert('Modal not found!');
    }
}

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

// Edit booking function (Bootstrap 5 compatible)
function editBooking(id) {
    console.log('Edit booking clicked for ID:', id);
    
    // Get modal element
    const modalElement = document.getElementById('editBookingModal');
    if (!modalElement) {
        alert('Modal element not found!');
        return;
    }
    
    // Show modal using Bootstrap 5 syntax
    const modal = new bootstrap.Modal(modalElement, {
        keyboard: false
    });
    modal.show();
    
    // Populate form data
    const bookingCard = document.querySelector(`[data-booking-id="${id}"]`);
    if (bookingCard) {
        document.getElementById('editCustomerId').value = bookingCard.dataset.customerId || '';
        document.getElementById('editStatus').value = bookingCard.dataset.status || 'pending';
        document.getElementById('editBookingDate').value = bookingCard.dataset.bookingDate || '';
        document.getElementById('editDeliveryDate').value = bookingCard.dataset.deliveryDate || '';
        document.getElementById('editPriority').value = bookingCard.dataset.priority || '';
        document.getElementById('editTotalAmount').value = bookingCard.dataset.totalAmount || '';
        document.getElementById('editNotes').value = bookingCard.dataset.notes || '';
        document.getElementById('editBookingForm').setAttribute('data-booking-id', id);
    } else {
        console.log('Booking card not found for ID:', id);
    }
}

// Populate edit form with booking data
function populateEditForm(booking) {
    document.getElementById('editCustomerId').value = booking.customer_id;
    document.getElementById('editStatus').value = booking.status;
    document.getElementById('editBookingDate').value = booking.booking_date;
    document.getElementById('editDeliveryDate').value = booking.delivery_date;
    document.getElementById('editPriority').value = booking.priority || '';
    document.getElementById('editTotalAmount').value = booking.total_amount;
    document.getElementById('editNotes').value = booking.notes || '';
    
    // Set form action
    document.getElementById('editBookingForm').setAttribute('data-booking-id', booking.id);
}

// Handle form submission
document.getElementById('editBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const bookingId = this.getAttribute('data-booking-id');
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    fetch(`/Dashboard/bookings/${bookingId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('editBookingModal')).hide();
            
            // Show success message
            alert('Booking updated successfully!');
            
            // Reload page to show updated data
            location.reload();
        } else {
            alert('Error updating booking: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating booking');
    });
});
    });
}
</script>
@endsection