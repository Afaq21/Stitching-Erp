@extends('layouts.master')

@section('title', 'Payments Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Payments Management</h2>
                    <p class="text-muted mb-0">Track and manage all customer payments</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('Dashboard.payments.today') }}" class="btn btn-outline-primary">
                        <i class="ri-calendar-line me-2"></i>Today's Payments
                    </a>
                    <a href="{{ route('Dashboard.payments.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-2"></i>Add Payment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 col-12 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary rounded-circle me-3">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                        <div>
                            <h3 class="mb-1">{{ $stats['total_payments'] }}</h3>
                            <p class="text-muted mb-0">Total Payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success bg-opacity-10 text-success rounded-circle me-3">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div>
                            <h3 class="mb-1">{{ $stats['today_payments'] }}</h3>
                            <p class="text-muted mb-0">Today's Payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info bg-opacity-10 text-info rounded-circle me-3">
                            <i class="ri-wallet-3-line"></i>
                        </div>
                        <div>
                            <h3 class="mb-1">Rs{{ number_format($stats['total_amount'], 0) }}</h3>
                            <p class="text-muted mb-0">Total Amount</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning bg-opacity-10 text-warning rounded-circle me-3">
                            <i class="ri-calendar-todo-line"></i>
                        </div>
                        <div>
                            <h3 class="mb-1">Rs{{ number_format($stats['today_amount'], 0) }}</h3>
                            <p class="text-muted mb-0">Today's Amount</p>
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
                    <h5 class="card-title mb-0">Recent Payments</h5>
                    <!-- Search Bar -->
                    <div class="search-container">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" class="form-control search-input" id="searchInput"
                               placeholder="Search by customer name or phone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-responsive" id="gridContainer">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Customer</th>
                                        <th>Booking</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Method</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <span class="fw-medium">#{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="fw-medium">{{ $payment->customer->name }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $payment->customer->phone }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('Dashboard.bookings.show', $payment->booking) }}"
                                                    class="text-decoration-none">
                                                    #{{ str_pad($payment->booking->id, 6, '0', STR_PAD_LEFT) }}
                                                </a>
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
                                                        @if($payment->status !== 'completed')
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('Dashboard.payments.destroy', $payment) }}"
                                                                        method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger"
                                                                            onclick="return confirm('Are you sure?')">
                                                                        <i class="ri-delete-bin-line me-2"></i>Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4" id="paginationContainer">
                            {{ $payments->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-money-dollar-circle-line display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No payments found</h5>
                            <p class="text-muted">Start by adding your first payment</p>
                            <a href="{{ route('Dashboard.payments.create') }}" class="btn btn-primary">
                                <i class="ri-add-line me-2"></i>Add Payment
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
.stats-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.stats-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
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
            document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
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
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                }

                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }
});
</script>
@endsection
