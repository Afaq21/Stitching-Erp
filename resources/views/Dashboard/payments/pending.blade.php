@extends('layouts.master')

@section('css')
<style>
    .pending-header {
        background: linear-gradient(135deg, #405189 0%, #5a6fb8 100%);
        border-radius: 15px;
        color: white;
    }
    
    .payment-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border-left: 4px solid #ffc107;
    }
    
    .payment-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .payment-card.high-amount {
        border-left-color: #dc3545;
    }
    
    .payment-card.medium-amount {
        border-left-color: #fd7e14;
    }
    
    .payment-card.low-amount {
        border-left-color: #28a745;
    }
    
    .amount-highlight {
        font-size: 1.25rem;
        font-weight: 600;
        color: #dc3545;
    }
    
    .payment-progress {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
    }
    
    .payment-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    .total-pending {
        background: linear-gradient(135deg, #7790e2ff 0%, #c7cbdbff 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
    }
    
    .stats-card {
        border-radius: 12px;
        border: none;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    
    /* Pagination Styling */
    .pagination-rounded .page-link {
        border-radius: 50px;
        margin: 0 2px;
        border: 1px solid #e9ecef;
        color: #6c757d;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }
    
    .pagination-rounded .page-item.active .page-link {
        background: linear-gradient(135deg, #405189 0%, #5a6fb8 100%);
        border-color: #405189;
        color: white;
        box-shadow: 0 4px 15px rgba(64, 81, 137, 0.3);
    }
    
    .pagination-rounded .page-link:hover {
        background-color: #f8f9fa;
        border-color: #405189;
        color: #405189;
        transform: translateY(-1px);
    }
    
    .pagination-rounded .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #adb5bd;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <!-- Header -->
    <div class="pending-header p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="mb-1 text-white">
                    <i class="ri-money-rupee-circle-line me-2"></i>Pending Payments
                </h4>
                <p class="mb-0 opacity-75">Track and manage outstanding payments</p>
            </div>
            <div class="col-md-3">
                <!-- Search -->
                <div class="position-relative">
                    <i class="ri-search-line position-absolute" style="left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                    <input type="text" class="form-control" id="searchPendingPayments" 
                           placeholder="Search by customer..." 
                           style="padding-left: 2.5rem; border-radius: 8px;"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="total-pending">
                    <h3 class="mb-0" id="totalPendingAmount">Rs{{ number_format($bookings->sum('remaining_amount'), 0) }}</h3>
                    <small>Total Pending</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    @if($bookings->total() > 0)
        <div class="row mb-4" id="statsContainer">
            <div class="col-md-3">
                <div class="card stats-card text-center border-0" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                    <div class="card-body">
                        <h4 class="mb-1 text-dark">{{ $bookings->total() }}</h4>
                        <small class="text-dark opacity-75">Pending Bookings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center border-0" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <div class="card-body">
                        <h4 class="mb-1 text-dark">Rs{{ number_format($bookings->where('remaining_amount', '>', 5000)->sum('remaining_amount'), 0) }}</h4>
                        <small class="text-dark opacity-75">High Amount</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center border-0" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%);">
                    <div class="card-body">
                        <h4 class="mb-1 text-dark">{{ $bookings->where('delivery_date', '<', now())->count() }}</h4>
                        <small class="text-dark opacity-75">Overdue</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center border-0" style="background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);">
                    <div class="card-body text-white">
                        <h4 class="mb-1">Rs{{ number_format($bookings->avg('remaining_amount'), 0) }}</h4>
                        <small class="opacity-75">Average Pending</small>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Pending Payments List -->
    <div class="row" id="pendingPaymentsGrid">
        @forelse($bookings as $booking)
            @if($booking->remaining_amount > 0)
                @php
                    $amountClass = '';
                    if ($booking->remaining_amount > 5000) $amountClass = 'high-amount';
                    elseif ($booking->remaining_amount > 2000) $amountClass = 'medium-amount';
                    else $amountClass = 'low-amount';
                    
                    $paymentPercentage = (($booking->total_amount - $booking->remaining_amount) / $booking->total_amount) * 100;
                @endphp
            
            <div class="col-xl-6 mb-4">
                <div class="card payment-card {{ $amountClass }}">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="mb-1">{{ $booking->customer->name }}</h6>
                                <small class="text-muted">
                                    <i class="ri-phone-line me-1"></i>{{ $booking->customer->phone }}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $booking->status_badge }}">
                                    {{ $booking->status_text }}
                                </span>
                                @if($booking->delivery_date < now())
                                    <br><small class="text-danger">Overdue</small>
                                @endif
                            </div>
                        </div>

                        <!-- Services Info -->
                        <div class="mb-3">
                            @foreach($booking->bookingItems->take(2) as $item)
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-scissors-line text-primary me-2"></i>
                                    <span>{{ $item->service->name }}</span>
                                    @if($item->quantity > 1)
                                        <span class="badge bg-secondary ms-2">x{{ $item->quantity }}</span>
                                    @endif
                                </div>
                            @endforeach
                            @if($booking->bookingItems->count() > 2)
                                <small class="text-muted">+{{ $booking->bookingItems->count() - 2 }} more</small>
                            @endif
                        </div>

                        <!-- Payment Details -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Amount:</span>
                                <strong>Rs{{ number_format($booking->total_amount, 0) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Paid:</span>
                                <span class="text-success">Rs{{ number_format($booking->advance_amount, 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Remaining:</strong>
                                <strong class="amount-highlight">Rs{{ number_format($booking->remaining_amount, 0) }}</strong>
                            </div>
                            
                            <!-- Payment Progress -->
                            <div class="payment-progress">
                                <div class="payment-progress-bar" style="width: {{ $paymentPercentage }}%"></div>
                            </div>
                            <small class="text-muted">{{ number_format($paymentPercentage, 1) }}% paid</small>
                        </div>

                        <!-- Dates -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Booking Date</small>
                                <span class="small">{{ $booking->booking_date->format('M d, Y') }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Delivery Date</small>
                                <span class="small {{ $booking->delivery_date < now() ? 'text-danger' : '' }}">
                                    {{ $booking->delivery_date->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('Dashboard.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="ri-eye-line me-1"></i>View
                            </a>
                            <a href="{{ route('Dashboard.payments.create', ['booking_id' => $booking->id]) }}" class="btn btn-sm btn-success flex-fill">
                                <i class="ri-add-line me-1"></i>Add Payment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="ri-money-rupee-circle-line display-1 text-success"></i>
                    </div>
                    <h5 class="text-muted mb-3">No Pending Payments</h5>
                    <p class="text-muted mb-4">Great! All your bookings have been fully paid.</p>
                    <a href="{{ route('Dashboard.bookings.index') }}" class="btn btn-primary">
                        <i class="ri-arrow-left-line me-1"></i>Back to All Bookings
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="d-flex justify-content-center mt-4" id="paginationContainer">
            <nav aria-label="Pending payments pagination">
                <ul class="pagination pagination-rounded mb-0">
                    {{-- Previous Page Link --}}
                    @if ($bookings->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link"><i class="ri-arrow-left-line"></i></span>
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
                            <span class="page-link"><i class="ri-arrow-right-line"></i></span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif
</div>

@endsection

@section('script')
<script>
// Search Functionality with AJAX (same as customers/bookings module)
let searchTimeout;
document.getElementById('searchPendingPayments').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadPendingPayments(e.target.value);
    }, 500);
});

// Load pending payments via AJAX
function loadPendingPayments(search = '') {
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
        const newGrid = doc.querySelector('#pendingPaymentsGrid');
        const newPagination = doc.querySelector('#paginationContainer');
        const newStats = doc.querySelector('#statsContainer');

        if (newGrid) {
            document.getElementById('pendingPaymentsGrid').innerHTML = newGrid.innerHTML;
        }
        if (newPagination) {
            document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
        }
        if (newStats) {
            document.getElementById('statsContainer').innerHTML = newStats.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error loading pending payments:', error);
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
                const newGrid = doc.querySelector('#pendingPaymentsGrid');
                const newPagination = doc.querySelector('#paginationContainer');
                const newStats = doc.querySelector('#statsContainer');

                if (newGrid) {
                    document.getElementById('pendingPaymentsGrid').innerHTML = newGrid.innerHTML;
                }
                if (newPagination) {
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                }
                if (newStats) {
                    document.getElementById('statsContainer').innerHTML = newStats.innerHTML;
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
</script>
@endsection
