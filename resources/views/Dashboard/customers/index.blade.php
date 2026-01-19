@extends('layouts.master')

@section('title', 'Customers')

@section('css')
<style>
/* Modern Card Styles - Professional Theme */
.customer-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: #fff;
    color: #495057;
    cursor: pointer;
    position: relative;
    height: 220px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.customer-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #405189;
}

.customer-card.flipped {
    background: #f8f9fa;
    border-color: #405189;
}

.card-front, .card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    transition: transform 0.6s;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.card-back {
    transform: rotateY(180deg);
    background: #f8f9fa;
}

.customer-card.flipped .card-front {
    transform: rotateY(180deg);
}

.customer-card.flipped .card-back {
    transform: rotateY(0deg);
}

.customer-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.customer-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #6c757d;
}

.customer-meta i {
    color: #405189;
}

.customer-phone {
    font-size: 1.1rem;
    font-weight: 600;
    color: #405189;
    margin-top: 0.5rem;
}

.customer-gender-badge {
    display: inline-block;
    background: #405189;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.card-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 2rem;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.btn-card {
    background: #fff;
    border: 2px solid #dee2e6;
    color: #6c757d;
    border-radius: 8px;
    padding: 0.75rem 1.25rem;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    min-width: 80px;
    flex: 1;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-card.btn-edit {
    border-color: #28a745;
    color: #28a745;
}

.btn-card.btn-edit:hover {
    background: #28a745;
    color: white;
}

.btn-card.btn-delete {
    border-color: #dc3545;
    color: #dc3545;
}

.btn-card.btn-delete:hover {
    background: #dc3545;
    color: white;
}

/* Add Button */
.add-customer-card {
    border: 2px dashed #405189;
    background: rgba(64,81,137,0.05);
    color: #405189;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 220px;
    border-radius: 12px;
}

.add-customer-card:hover {
    background: rgba(64,81,137,0.1);
    transform: translateY(-2px);
}

.add-icon {
    font-size: 2.5rem;
    opacity: 0.7;
}

/* Search Bar */
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

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px 12px 0 0;
}

.modal-title {
    color: #212529;
    font-weight: 600;
}

.modal-body {
    padding: 1.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #405189;
    box-shadow: 0 0 0 0.2rem rgba(64,81,137,0.25);
}

.form-label {
    color: #495057;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination .page-link {
    border: 1px solid #dee2e6;
    background: #fff;
    color: #6c757d;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: #405189;
    color: white;
    border-color: #405189;
}

.pagination .page-item.active .page-link {
    background: #405189;
    color: white;
    border-color: #405189;
}

/* Responsive */
@media (max-width: 768px) {
    .customer-card {
        height: 200px;
    }

    .customer-name {
        font-size: 1rem;
    }

    .customer-phone {
        font-size: 1rem;
    }

    .card-actions {
        flex-direction: row;
        gap: 0.25rem;
        padding-top: 0.5rem;
    }

    .btn-card {
        justify-content: center;
        padding: 0.4rem 0.6rem;
        font-size: 0.75rem;
        min-width: 60px;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">Customers</h2>
                    <p class="text-muted mb-0">Manage your customer database</p>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Search -->
                    <div class="search-container">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" class="form-control search-input" id="searchCustomers"
                               placeholder="Search customers..." value="{{ request('search') }}">
                    </div>

                    <!-- Add Customer Button -->
                    <button class="btn btn-primary" onclick="openCustomerModal()">
                        <i class="ri-add-line me-2"></i>Add Customer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Grid -->
    <div class="row" id="customersGrid">
        <!-- Add Customer Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="add-customer-card" onclick="openCustomerModal()">
                <i class="ri-add-circle-line add-icon"></i>
                <span class="fw-medium">Add New Customer</span>
            </div>
        </div>

        <!-- Customers Cards -->
        @foreach($customers as $customer)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-customer-id="{{ $customer->id }}">
                <div class="customer-card" onclick="flipCard(this)">

                    <!-- Front Side -->
                    <div class="card-front">
                        <div class="text-center">
                            <div class="customer-gender-badge">{{ ucfirst($customer->gender) }}</div>
                            <div class="customer-name">{{ $customer->name }}</div>
                            <div class="customer-meta">
                                <i class="ri-phone-line"></i>
                                <span>{{ $customer->phone }}</span>
                            </div>
                            @if($customer->address)
                                <div class="customer-meta mt-2">
                                    <i class="ri-map-pin-line"></i>
                                    <span class="text-truncate" style="max-width: 150px;">{{ $customer->address }}</span>
                                </div>
                            @endif
                            <small class="text-muted mt-3 d-block">Click to see options</small>
                        </div>
                    </div>

                    <!-- Back Side -->
                    <div class="card-back">
                        <div class="text-center mb-3">
                            <div class="customer-name mb-2">{{ $customer->name }}</div>
                            <div class="customer-phone mb-2">{{ $customer->phone }}</div>
                            <small class="text-muted">{{ ucfirst($customer->gender) }}</small>
                            @if($customer->address)
                                <div class="mt-2">
                                    <small class="text-muted"><i class="ri-map-pin-line"></i> {{ Str::limit($customer->address, 30) }}</small>
                                </div>
                            @endif
                        </div>
                        <div class="card-actions">
                            <button class="btn-card btn-edit" onclick="event.stopPropagation(); editCustomer({{ $customer->id }})">
                                <i class="ri-edit-line me-1"></i>Edit
                            </button>
                            <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteCustomer({{ $customer->id }})">
                                <i class="ri-delete-bin-line me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
        <div class="pagination-container" id="paginationContainer">
            {{ $customers->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalTitle">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="customerForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="customerName" name="name" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customerPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerPhone" name="phone" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="customerGender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" id="customerGender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="customerAddress" class="form-label">Address</label>
                        <textarea class="form-control" id="customerAddress" name="address" rows="3"></textarea>
                    </div>

                    <input type="hidden" id="customerId" name="customer_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-2"></i><span id="submitText">Add Customer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
let currentEditId = null;

// Open Customer Modal
function openCustomerModal(customerId = null) {
    const modal = new bootstrap.Modal(document.getElementById('customerModal'));
    const form = document.getElementById('customerForm');
    const title = document.getElementById('customerModalTitle');
    const submitText = document.getElementById('submitText');

    // Reset form
    form.reset();
    currentEditId = customerId;

    if (customerId) {
        // Edit mode
        title.textContent = 'Edit Customer';
        submitText.textContent = 'Update Customer';

        // Fetch customer data
        fetch(`/Dashboard/customers/${customerId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('customerName').value = data.name;
                document.getElementById('customerPhone').value = data.phone;
                document.getElementById('customerGender').value = data.gender;
                document.getElementById('customerAddress').value = data.address || '';
                document.getElementById('customerId').value = data.id;
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showToast('Error loading customer data', 'error');
            });
    } else {
        // Create mode
        title.textContent = 'Add New Customer';
        submitText.textContent = 'Add Customer';
        document.getElementById('customerId').value = '';
    }

    modal.show();
}

// Edit Customer
function editCustomer(customerId) {
    openCustomerModal(customerId);
}

// Delete Customer
function deleteCustomer(customerId) {
    if (confirm('Are you sure you want to delete this customer?')) {
        fetch(`/Dashboard/customers/${customerId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove card from grid with animation
                const card = document.querySelector(`[data-customer-id="${customerId}"]`);
                if (card) {
                    card.style.transform = 'scale(0)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                    }, 300);
                }
                showToast('Customer deleted successfully!', 'success');
            } else {
                showToast('Error deleting customer: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting customer', 'error');
        });
    }
}

// Flip Card
function flipCard(card) {
    card.classList.toggle('flipped');
}

// Form Submission
document.getElementById('customerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const customerId = document.getElementById('customerId').value;

    let url = '/Dashboard/customers';
    let method = 'POST';

    if (customerId) {
        url = `/Dashboard/customers/${customerId}`;
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('customerModal')).hide();

            if (customerId) {
                // Update existing card
                updateCustomerCard(data.customer);
                showToast('Customer updated successfully!', 'success');
            } else {
                // Add new card
                addCustomerCard(data.customer);
                showToast('Customer added successfully!', 'success');
            }
        } else {
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat().join('<br>');
                showToast(errorMessages, 'error');
            } else {
                showToast('Error: ' + (data.message || 'Unknown error'), 'error');
            }
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('Error saving customer', 'error');
    });
});

// Search Functionality with AJAX
let searchTimeout;
document.getElementById('searchCustomers').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadCustomers(e.target.value);
    }, 500);
});

// Load customers via AJAX
function loadCustomers(search = '') {
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
        const newGrid = doc.querySelector('#customersGrid');
        const newPagination = doc.querySelector('#paginationContainer');

        if (newGrid) {
            document.getElementById('customersGrid').innerHTML = newGrid.innerHTML;
        }
        if (newPagination) {
            document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error loading customers:', error);
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
                const newGrid = doc.querySelector('#customersGrid');
                const newPagination = doc.querySelector('#paginationContainer');

                if (newGrid) {
                    document.getElementById('customersGrid').innerHTML = newGrid.innerHTML;
                }
                if (newPagination) {
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                }

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }
});

// Add new customer card to grid
function addCustomerCard(customer) {
    const customersGrid = document.getElementById('customersGrid');
    const addCard = customersGrid.querySelector('.add-customer-card').parentElement;

    const addressHtml = customer.address ? `
        <div class="customer-meta mt-2">
            <i class="ri-map-pin-line"></i>
            <span class="text-truncate" style="max-width: 150px;">${customer.address}</span>
        </div>
    ` : '';

    const addressBackHtml = customer.address ? `
        <div class="mt-2">
            <small class="text-muted"><i class="ri-map-pin-line"></i> ${customer.address.substring(0, 30)}${customer.address.length > 30 ? '...' : ''}</small>
        </div>
    ` : '';

    const newCardHtml = `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-customer-id="${customer.id}" style="transform: scale(0); opacity: 0;">
            <div class="customer-card" onclick="flipCard(this)">
                <div class="card-front">
                    <div class="text-center">
                        <div class="customer-gender-badge">${customer.gender.charAt(0).toUpperCase() + customer.gender.slice(1)}</div>
                        <div class="customer-name">${customer.name}</div>
                        <div class="customer-meta">
                            <i class="ri-phone-line"></i>
                            <span>${customer.phone}</span>
                        </div>
                        ${addressHtml}
                        <small class="text-muted mt-3 d-block">Click to see options</small>
                    </div>
                </div>
                <div class="card-back">
                    <div class="text-center mb-3">
                        <div class="customer-name mb-2">${customer.name}</div>
                        <div class="customer-phone mb-2">${customer.phone}</div>
                        <small class="text-muted">${customer.gender.charAt(0).toUpperCase() + customer.gender.slice(1)}</small>
                        ${addressBackHtml}
                    </div>
                    <div class="card-actions">
                        <button class="btn-card btn-edit" onclick="event.stopPropagation(); editCustomer(${customer.id})">
                            <i class="ri-edit-line me-1"></i>Edit
                        </button>
                        <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteCustomer(${customer.id})">
                            <i class="ri-delete-bin-line me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    addCard.insertAdjacentHTML('afterend', newCardHtml);

    const newCard = document.querySelector(`[data-customer-id="${customer.id}"]`);
    setTimeout(() => {
        newCard.style.transform = 'scale(1)';
        newCard.style.opacity = '1';
        newCard.style.transition = 'all 0.3s ease';
    }, 100);
}

// Update existing customer card
function updateCustomerCard(customer) {
    const card = document.querySelector(`[data-customer-id="${customer.id}"]`);
    if (!card) return;

    const addressHtml = customer.address ? `
        <div class="customer-meta mt-2">
            <i class="ri-map-pin-line"></i>
            <span class="text-truncate" style="max-width: 150px;">${customer.address}</span>
        </div>
    ` : '';

    const addressBackHtml = customer.address ? `
        <div class="mt-2">
            <small class="text-muted"><i class="ri-map-pin-line"></i> ${customer.address.substring(0, 30)}${customer.address.length > 30 ? '...' : ''}</small>
        </div>
    ` : '';

    card.innerHTML = `
        <div class="customer-card" onclick="flipCard(this)">
            <div class="card-front">
                <div class="text-center">
                    <div class="customer-gender-badge">${customer.gender.charAt(0).toUpperCase() + customer.gender.slice(1)}</div>
                    <div class="customer-name">${customer.name}</div>
                    <div class="customer-meta">
                        <i class="ri-phone-line"></i>
                        <span>${customer.phone}</span>
                    </div>
                    ${addressHtml}
                    <small class="text-muted mt-3 d-block">Click to see options</small>
                </div>
            </div>
            <div class="card-back">
                <div class="text-center mb-3">
                    <div class="customer-name mb-2">${customer.name}</div>
                    <div class="customer-phone mb-2">${customer.phone}</div>
                    <small class="text-muted">${customer.gender.charAt(0).toUpperCase() + customer.gender.slice(1)}</small>
                    ${addressBackHtml}
                </div>
                <div class="card-actions">
                    <button class="btn-card btn-edit" onclick="event.stopPropagation(); editCustomer(${customer.id})">
                        <i class="ri-edit-line me-1"></i>Edit
                    </button>
                    <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteCustomer(${customer.id})">
                        <i class="ri-delete-bin-line me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    `;

    card.style.transform = 'scale(1.02)';
    setTimeout(() => {
        card.style.transform = 'scale(1)';
    }, 200);
}

// Toast notification system
function showToast(message, type = 'success') {
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.className = `custom-toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="ri-${type === 'success' ? 'check-circle' : 'error-warning'}-line"></i>
            <span>${message}</span>
        </div>
    `;

    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);

    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}
</script>
@endsection
