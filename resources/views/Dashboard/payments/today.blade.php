@extends('layouts.master')

@section('title', "Today's Payments")

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Today's Payments</h2>
                    <p class="text-muted mb-0">{{ date('l, F d, Y') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('Dashboard.payments.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-2"></i>Add Payment
                    </a>
                    <a href="{{ route('Dashboard.payments.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>All Payments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="text-white mb-1">Rs{{ number_format($totalAmount, 0) }}</h3>
                            <p class="text-white-50 mb-0">Total payments received today</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="inline-size: 60px; block-size: 60px;">
                                <i class="ri-money-dollar-circle-line fs-3 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Today's Payment Transactions ({{ $payments->total() }})</h5>
                    <!-- Search Bar -->
                    <div class="search-container">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" class="form-control search-input" id="searchInput"
                               placeholder="Search by customer name or phone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="row" id="gridContainer">
                            @foreach($payments as $payment)
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <div class="card h-100 payment-card">
                                        <div class="card-body">
                                            <!-- Header -->
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h6 class="mb-1">#{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</h6>
                                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                                </div>
                                                <span class="badge {{ $payment->status_badge }}">{{ $payment->status_text }}</span>
                                            </div>

                                            <!-- Customer -->
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                         style="inline-size: 40px; block-size: 40px;">
                                                        <i class="ri-user-line"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $payment->customer->name }}</div>
                                                        <small class="text-muted">{{ $payment->customer->phone }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Amount -->
                                            <div class="mb-3">
                                                <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                                    <h4 class="text-success mb-1">Rs{{ number_format($payment->amount, 0) }}</h4>
                                                    <small class="text-muted">{{ $payment->payment_type_text }}</small>
                                                </div>
                                            </div>

                                            <!-- Details -->
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted">Method</small>
                                                        <div class="fw-medium">{{ $payment->payment_method_text }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Booking</small>
                                                        <div>
                                                            <a href="{{ route('Dashboard.bookings.show', $payment->booking) }}"
                                                                class="text-decoration-none">
                                                                #{{ str_pad($payment->booking->id, 6, '0', STR_PAD_LEFT) }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($payment->reference_number)
                                                <div class="mb-3">
                                                    <small class="text-muted">Reference</small>
                                                    <div class="fw-medium">{{ $payment->reference_number }}</div>
                                                </div>
                                            @endif

                                            <!-- Actions -->
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('Dashboard.payments.show', $payment) }}"
                                                    class="btn btn-outline-primary btn-sm flex-fill">
                                                    <i class="ri-eye-line me-1"></i>View
                                                </a>
                                                <a href="{{ route('Dashboard.payments.edit', $payment) }}"
                                                    class="btn btn-outline-secondary btn-sm flex-fill">
                                                    <i class="ri-edit-line me-1"></i>Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($payments->hasPages())
                            <div class="d-flex justify-content-center mt-4" id="paginationContainer">
                                {{ $payments->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="ri-calendar-line display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No payments today</h5>
                            <p class="text-muted">No payments have been recorded for today yet</p>
                            <a href="{{ route('Dashboard.payments.create') }}" class="btn btn-primary">
                                <i class="ri-add-line me-2"></i>Add First Payment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.payment-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.payment-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.search-container {
    position: relative;
    max-width: 300px;
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
</script>
@endsection
