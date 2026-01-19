@extends('layouts.master')

@section('title', 'Add-on Services')

@section('css')
<style>
/* Modern Card Styles - Professional Theme (Addon) */
.service-card {
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

.service-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #f06548;
}

.service-card.flipped {
    background: #fff5f3;
    border-color: #f06548;
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
    background: #fff5f3;
}

.service-card.flipped .card-front {
    transform: rotateY(180deg);
}

.service-card.flipped .card-back {
    transform: rotateY(0deg);
}

.service-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.service-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #6c757d;
}

.service-meta i {
    color: #f06548;
}

.service-price {
    font-size: 1.4rem;
    font-weight: 700;
    color: #f06548;
    margin-top: 0.5rem;
}

.service-category-badge {
    display: inline-block;
    background: #f06548;
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
.add-service-card {
    border: 2px dashed #f06548;
    background: rgba(240,101,72,0.05);
    color: #f06548;
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

.add-service-card:hover {
    background: rgba(240,101,72,0.1);
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
    border-color: #f06548;
    box-shadow: 0 0 0 0.2rem rgba(240,101,72,0.25);
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

/* Custom Button */
.btn-addon {
    background: #f06548;
    border: 1px solid #f06548;
    color: white;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.btn-addon:hover {
    background: #e55a42;
    border-color: #e55a42;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(240,101,72,0.3);
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal-header {
    border-bottom: 1px solid #eee;
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #f5576c;
    box-shadow: 0 0 0 0.2rem rgba(245,87,108,0.25);
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination .page-link {
    border: none;
    background: transparent;
    color: #6c757d;
    padding: 0.5rem 0.75rem;
    margin: 0 0.25rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: #f5576c;
    color: white;
}

.pagination .page-item.active .page-link {
    background: #f5576c;
    color: white;
}

/* Custom Button */
.btn-gradient-pink {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
    color: white;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.btn-gradient-pink:hover {
    background: linear-gradient(135deg, #e084fc 0%, #e6495d 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(245,87,108,0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .service-card {
        height: 200px;
    }
    
    .service-title {
        font-size: 1rem;
    }
    
    .service-price {
        font-size: 1.2rem;
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
                    <h2 class="mb-1">Add-on Services</h2>
                    <p class="text-muted mb-0">Manage your additional tailoring services</p>
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Search -->
                    <div class="search-container">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" class="form-control search-input" id="searchServices" 
                               placeholder="Search services..." value="{{ request('search') }}">
                    </div>
                    
                    <!-- Add Service Button -->
                    <button class="btn btn-addon" onclick="openServiceModal()">
                        <i class="ri-add-line me-2"></i>Add Service
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="row" id="servicesGrid">
        <!-- Add Service Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="add-service-card" onclick="openServiceModal()">
                <i class="ri-add-circle-line add-icon"></i>
                <span class="fw-medium">Add New Service</span>
            </div>
        </div>

        <!-- Services Cards -->
        @foreach($services as $service)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-service-id="{{ $service->id }}">
                <div class="service-card" draggable="true" onclick="flipCard(this)" 
                     ondragstart="dragStart(event)" ondragend="dragEnd(event)">
                    
                    <!-- Front Side -->
                    <div class="card-front">
                        <div class="text-center">
                            <div class="service-category-badge">Add-on Service</div>
                            <div class="service-title">{{ $service->name }}</div>
                            <div class="service-meta">
                                <i class="ri-user-line"></i>
                                <span>{{ ucfirst($service->gender) }}</span>
                            </div>
                            <div class="service-price mt-3">Rs{{ number_format($service->price, 0) }}</div>
                            <small class="text-muted">Click to see options</small>
                        </div>
                    </div>

                    <!-- Back Side -->
                    <div class="card-back">
                        <div class="text-center mb-3">
                            <div class="service-title mb-2">{{ $service->name }}</div>
                            <div class="service-price mb-2" style="color: #f06548;">Rs{{ number_format($service->price, 0) }}</div>
                            <small class="text-muted">{{ ucfirst($service->gender) }} • {{ ucfirst($service->service_category) }}</small>
                        </div>
                        <div class="card-actions">
                            <button class="btn-card btn-edit" onclick="event.stopPropagation(); editService({{ $service->id }})">
                                <i class="ri-edit-line me-1"></i>Edit
                            </button>
                            <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteService({{ $service->id }})">
                                <i class="ri-delete-bin-line me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($services->hasPages())
        <div class="pagination-container" id="paginationContainer">
            {{ $services->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Service Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalTitle">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="serviceForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="serviceName" class="form-label">Service Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="serviceName" name="name" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="serviceGender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" id="serviceGender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="both">Both</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="servicePrice" class="form-label">Price (Rs) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="servicePrice" name="price" min="0" step="0.01" required>
                        </div>
                    </div>
                    
                    <input type="hidden" name="service_category" value="addon">
                    <input type="hidden" id="serviceId" name="service_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-addon">
                        <i class="ri-save-line me-2"></i><span id="submitText">Add Service</span>
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

// Open Service Modal
function openServiceModal(serviceId = null) {
    const modal = new bootstrap.Modal(document.getElementById('serviceModal'));
    const form = document.getElementById('serviceForm');
    const title = document.getElementById('serviceModalTitle');
    const submitText = document.getElementById('submitText');
    
    // Reset form
    form.reset();
    currentEditId = serviceId;
    
    if (serviceId) {
        // Edit mode
        title.textContent = 'Edit Service';
        submitText.textContent = 'Update Service';
        
        // Fetch service data
        fetch(`/Dashboard/services/${serviceId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Edit data received:', data);
                if (data.success) {
                    document.getElementById('serviceName').value = data.service.name;
                    document.getElementById('serviceGender').value = data.service.gender;
                    document.getElementById('servicePrice').value = data.service.price;
                    document.getElementById('serviceId').value = data.service.id;
                } else {
                    console.error('Error in response:', data.message);
                    alert('Error loading service data: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error loading service data. Please try again.');
            });
    } else {
        // Create mode
        title.textContent = 'Add New Service';
        submitText.textContent = 'Add Service';
        document.getElementById('serviceId').value = '';
    }
    
    modal.show();
}

// Edit Service
function editService(serviceId) {
    openServiceModal(serviceId);
}

// Delete Service
function deleteService(serviceId) {
    if (confirm('Are you sure you want to delete this service?')) {
        fetch(`/Dashboard/services/${serviceId}`, {
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
                const card = document.querySelector(`[data-service-id="${serviceId}"]`);
                if (card) {
                    card.style.transform = 'scale(0)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                    }, 300);
                }
                showToast('Service deleted successfully!', 'success');
            } else {
                showToast('Error deleting service: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting service', 'error');
        });
    }
}

// Flip Card
function flipCard(card) {
    card.classList.toggle('flipped');
}

// Form Submission
document.getElementById('serviceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const serviceId = document.getElementById('serviceId').value;
    
    let url = '/Dashboard/services';
    let method = 'POST';
    
    if (serviceId) {
        url = `/Dashboard/services/${serviceId}`;
        formData.append('_method', 'PUT');
    }
    
    console.log('Submitting form:', {
        url: url,
        method: method,
        serviceId: serviceId,
        formData: Object.fromEntries(formData)
    });
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('serviceModal')).hide();
            
            if (serviceId) {
                // Update existing card
                updateServiceCard(data.service);
                showToast('Service updated successfully!', 'success');
            } else {
                // Add new card
                addServiceCard(data.service);
                showToast('Service added successfully!', 'success');
            }
        } else {
            showToast('Error: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('Error saving service: ' + error.message, 'error');
    });
});

// Search Functionality with AJAX
let searchTimeout;
document.getElementById('searchServices').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadServices(e.target.value);
    }, 500);
});

// Load services via AJAX
function loadServices(search = '') {
    const url = new URL(window.location.origin + '/Dashboard/services/addon');
    if (search) {
        url.searchParams.set('search', search);
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
        const newGrid = doc.querySelector('#servicesGrid');
        const newPagination = doc.querySelector('#paginationContainer');

        if (newGrid) {
            document.getElementById('servicesGrid').innerHTML = newGrid.innerHTML;
        }
        if (newPagination) {
            document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error loading services:', error);
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
                const newGrid = doc.querySelector('#servicesGrid');
                const newPagination = doc.querySelector('#paginationContainer');

                if (newGrid) {
                    document.getElementById('servicesGrid').innerHTML = newGrid.innerHTML;
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

// Drag and Drop Functionality
let draggedElement = null;

function dragStart(e) {
    draggedElement = e.target.closest('[data-service-id]');
    draggedElement.classList.add('dragging');
}

function dragEnd(e) {
    if (draggedElement) {
        draggedElement.classList.remove('dragging');
        draggedElement = null;
    }
}

// Allow drop
document.addEventListener('dragover', function(e) {
    e.preventDefault();
});

// Handle drop
document.addEventListener('drop', function(e) {
    e.preventDefault();
    
    if (!draggedElement) return;
    
    const dropTarget = e.target.closest('[data-service-id]');
    if (dropTarget && dropTarget !== draggedElement) {
        // Swap positions
        const parent = draggedElement.parentNode;
        const nextSibling = dropTarget.nextSibling;
        
        parent.insertBefore(draggedElement, dropTarget);
        parent.insertBefore(dropTarget, nextSibling);
        
        console.log('Cards reordered');
    }
});

// Add new service card to grid
function addServiceCard(service) {
    const servicesGrid = document.getElementById('servicesGrid');
    const addCard = servicesGrid.querySelector('.add-service-card').parentElement;
    
    const newCardHtml = `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-service-id="${service.id}" style="transform: scale(0); opacity: 0;">
            <div class="service-card" draggable="true" onclick="flipCard(this)" 
                 ondragstart="dragStart(event)" ondragend="dragEnd(event)">
                
                <!-- Front Side -->
                <div class="card-front">
                    <div class="text-center">
                        <div class="service-category-badge">Add-on Service</div>
                        <div class="service-title">${service.name}</div>
                        <div class="service-meta">
                            <i class="ri-user-line"></i>
                            <span>${service.gender.charAt(0).toUpperCase() + service.gender.slice(1)}</span>
                        </div>
                        <div class="service-price mt-3">Rs${Number(service.price).toLocaleString()}</div>
                        <small class="text-muted">Click to see options</small>
                    </div>
                </div>

                <!-- Back Side -->
                <div class="card-back">
                    <div class="text-center mb-3">
                        <div class="service-title mb-2">${service.name}</div>
                        <div class="service-price mb-2" style="color: #f06548;">Rs${Number(service.price).toLocaleString()}</div>
                        <small class="text-muted">${service.gender.charAt(0).toUpperCase() + service.gender.slice(1)} • ${service.service_category.charAt(0).toUpperCase() + service.service_category.slice(1)}</small>
                    </div>
                    <div class="card-actions">
                        <button class="btn-card btn-edit" onclick="event.stopPropagation(); editService(${service.id})">
                            <i class="ri-edit-line me-1"></i>Edit
                        </button>
                        <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteService(${service.id})">
                            <i class="ri-delete-bin-line me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Insert after add card
    addCard.insertAdjacentHTML('afterend', newCardHtml);
    
    // Animate in
    const newCard = document.querySelector(`[data-service-id="${service.id}"]`);
    setTimeout(() => {
        newCard.style.transform = 'scale(1)';
        newCard.style.opacity = '1';
        newCard.style.transition = 'all 0.3s ease';
    }, 100);
}

// Update existing service card
function updateServiceCard(service) {
    const card = document.querySelector(`[data-service-id="${service.id}"]`);
    if (!card) return;
    
    // Update front side
    const frontTitle = card.querySelector('.card-front .service-title');
    const frontGender = card.querySelector('.card-front .service-meta span');
    const frontPrice = card.querySelector('.card-front .service-price');
    
    frontTitle.textContent = service.name;
    frontGender.textContent = service.gender.charAt(0).toUpperCase() + service.gender.slice(1);
    frontPrice.textContent = `Rs${Number(service.price).toLocaleString()}`;
    
    // Update back side
    const backTitle = card.querySelector('.card-back .service-title');
    const backPrice = card.querySelector('.card-back .service-price');
    const backInfo = card.querySelector('.card-back small');
    
    backTitle.textContent = service.name;
    backPrice.textContent = `Rs${Number(service.price).toLocaleString()}`;
    backInfo.textContent = `${service.gender.charAt(0).toUpperCase() + service.gender.slice(1)} • ${service.service_category.charAt(0).toUpperCase() + service.service_category.slice(1)}`;
    
    // Add update animation
    card.style.transform = 'scale(1.02)';
    setTimeout(() => {
        card.style.transform = 'scale(1)';
    }, 200);
}

// Toast notification system
function showToast(message, type = 'success') {
    // Remove existing toast
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
    
    // Add toast styles
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
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove
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