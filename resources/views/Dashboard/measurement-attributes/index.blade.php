@extends('layouts.master')

@section('title', 'Measurement Attributes')

@section('css')
<style>
/* Modern Card Styles - Professional Theme */
.attribute-card {
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

.attribute-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #405189;
}

.attribute-card.flipped {
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

.attribute-card.flipped .card-front {
    transform: rotateY(180deg);
}

.attribute-card.flipped .card-back {
    transform: rotateY(0deg);
}

.attribute-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.attribute-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #6c757d;
}

.attribute-meta i {
    color: #405189;
}

.attribute-badge {
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
.add-attribute-card {
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

.add-attribute-card:hover {
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
    .attribute-card {
        height: 200px;
    }

    .attribute-name {
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
                    <h2 class="mb-1">Measurement Attributes</h2>
                    <p class="text-muted mb-0">Manage measurement fields for services</p>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Search -->
                    <div class="search-container">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" class="form-control search-input" id="searchAttributes"
                               placeholder="Search attributes..." value="{{ request('search') }}">
                    </div>

                    <!-- Add Attribute Button -->
                    <button class="btn btn-primary" onclick="openAttributeModal()">
                        <i class="ri-add-line me-2"></i>Add Attribute
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Attributes Grid -->
    <div class="row" id="attributesGrid">
        <!-- Add Attribute Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="add-attribute-card" onclick="openAttributeModal()">
                <i class="ri-add-circle-line add-icon"></i>
                <span class="fw-medium">Add New Attribute</span>
            </div>
        </div>

        <!-- Attributes Cards -->
        @foreach($measurementAttributes as $attribute)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-attribute-id="{{ $attribute->id }}">
                <div class="attribute-card" onclick="flipCard(this)">

                    <!-- Front Side -->
                    <div class="card-front">
                        <div class="text-center">
                            <div class="attribute-badge">Measurement Field</div>
                            <div class="attribute-name">{{ $attribute->name }}</div>
                            <div class="attribute-meta">
                                <i class="ri-scissors-line"></i>
                                <span>{{ $attribute->service->name ?? 'N/A' }}</span>
                            </div>
                            <div class="attribute-meta mt-2">
                                <i class="ri-user-line"></i>
                                <span>{{ ucfirst($attribute->service->gender ?? 'N/A') }}</span>
                            </div>
                            <small class="text-muted mt-3 d-block">Click to see options</small>
                        </div>
                    </div>

                    <!-- Back Side -->
                    <div class="card-back">
                        <div class="text-center mb-3">
                            <div class="attribute-name mb-2">{{ $attribute->name }}</div>
                            <small class="text-muted d-block">{{ $attribute->service->name ?? 'N/A' }}</small>
                            <small class="text-muted d-block">{{ ucfirst($attribute->service->gender ?? 'N/A') }}</small>
                        </div>
                        <div class="card-actions">
                            <button class="btn-card btn-edit" onclick="event.stopPropagation(); editAttribute({{ $attribute->id }})">
                                <i class="ri-edit-line me-1"></i>Edit
                            </button>
                            <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteAttribute({{ $attribute->id }})">
                                <i class="ri-delete-bin-line me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($measurementAttributes->hasPages())
        <div class="pagination-container" id="paginationContainer">
            {{ $measurementAttributes->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Attribute Modal -->
<div class="modal fade" id="attributeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attributeModalTitle">Add New Attribute</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="attributeForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="attributeName" class="form-label">Attribute Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="attributeName" name="name" placeholder="e.g., Chest, Waist, Length" required>
                    </div>

                    <div class="mb-3">
                        <label for="serviceId" class="form-label">Service <span class="text-danger">*</span></label>
                        <select class="form-select" id="serviceId" name="service_id" required>
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }} ({{ ucfirst($service->gender) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" id="attributeId" name="attribute_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-2"></i><span id="submitText">Add Attribute</span>
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

// Open Attribute Modal
function openAttributeModal(attributeId = null) {
    const modal = new bootstrap.Modal(document.getElementById('attributeModal'));
    const form = document.getElementById('attributeForm');
    const title = document.getElementById('attributeModalTitle');
    const submitText = document.getElementById('submitText');

    // Reset form
    form.reset();
    currentEditId = attributeId;

    if (attributeId) {
        // Edit mode
        title.textContent = 'Edit Attribute';
        submitText.textContent = 'Update Attribute';

        // Fetch attribute data
        fetch(`/Dashboard/measurement-attributes/${attributeId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('attributeName').value = data.name;
                document.getElementById('serviceId').value = data.service_id;
                document.getElementById('attributeId').value = data.id;
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showToast('Error loading attribute data', 'error');
            });
    } else {
        // Create mode
        title.textContent = 'Add New Attribute';
        submitText.textContent = 'Add Attribute';
        document.getElementById('attributeId').value = '';
    }

    modal.show();
}

// Edit Attribute
function editAttribute(attributeId) {
    openAttributeModal(attributeId);
}

// Delete Attribute
function deleteAttribute(attributeId) {
    if (confirm('Are you sure you want to delete this attribute?')) {
        fetch(`/Dashboard/measurement-attributes/${attributeId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector(`[data-attribute-id="${attributeId}"]`);
                if (card) {
                    card.style.transform = 'scale(0)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                    }, 300);
                }
                showToast('Attribute deleted successfully!', 'success');
            } else {
                showToast('Error deleting attribute: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting attribute', 'error');
        });
    }
}

// Flip Card
function flipCard(card) {
    card.classList.toggle('flipped');
}

// Form Submission
document.getElementById('attributeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const attributeId = document.getElementById('attributeId').value;

    let url = '/Dashboard/measurement-attributes';
    let method = 'POST';

    if (attributeId) {
        url = `/Dashboard/measurement-attributes/${attributeId}`;
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
            bootstrap.Modal.getInstance(document.getElementById('attributeModal')).hide();

            if (attributeId) {
                updateAttributeCard(data.attribute);
                showToast('Attribute updated successfully!', 'success');
            } else {
                addAttributeCard(data.attribute);
                showToast('Attribute added successfully!', 'success');
            }
        } else {
            showToast('Error: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('Error saving attribute', 'error');
    });
});

// Search Functionality with AJAX
let searchTimeout;
document.getElementById('searchAttributes').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadAttributes(e.target.value);
    }, 500);
});

// Load attributes via AJAX
function loadAttributes(search = '') {
    const url = new URL(window.location.origin + '/Dashboard/measurement-attributes');
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
        const newGrid = doc.querySelector('#attributesGrid');
        const newPagination = doc.querySelector('#paginationContainer');

        if (newGrid) {
            document.getElementById('attributesGrid').innerHTML = newGrid.innerHTML;
        }
        if (newPagination) {
            document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error loading attributes:', error);
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
                const newGrid = doc.querySelector('#attributesGrid');
                const newPagination = doc.querySelector('#paginationContainer');

                if (newGrid) {
                    document.getElementById('attributesGrid').innerHTML = newGrid.innerHTML;
                }
                if (newPagination) {
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                }

                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }
});

// Add new attribute card to grid
function addAttributeCard(attribute) {
    const attributesGrid = document.getElementById('attributesGrid');
    const addCard = attributesGrid.querySelector('.add-attribute-card').parentElement;

    const newCardHtml = `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-attribute-id="${attribute.id}" style="transform: scale(0); opacity: 0;">
            <div class="attribute-card" onclick="flipCard(this)">
                <div class="card-front">
                    <div class="text-center">
                        <div class="attribute-badge">Measurement Field</div>
                        <div class="attribute-name">${attribute.name}</div>
                        <div class="attribute-meta">
                            <i class="ri-scissors-line"></i>
                            <span>${attribute.service.name}</span>
                        </div>
                        <div class="attribute-meta mt-2">
                            <i class="ri-user-line"></i>
                            <span>${attribute.service.gender.charAt(0).toUpperCase() + attribute.service.gender.slice(1)}</span>
                        </div>
                        <small class="text-muted mt-3 d-block">Click to see options</small>
                    </div>
                </div>
                <div class="card-back">
                    <div class="text-center mb-3">
                        <div class="attribute-name mb-2">${attribute.name}</div>
                        <small class="text-muted d-block">${attribute.service.name}</small>
                        <small class="text-muted d-block">${attribute.service.gender.charAt(0).toUpperCase() + attribute.service.gender.slice(1)}</small>
                    </div>
                    <div class="card-actions">
                        <button class="btn-card btn-edit" onclick="event.stopPropagation(); editAttribute(${attribute.id})">
                            <i class="ri-edit-line me-1"></i>Edit
                        </button>
                        <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteAttribute(${attribute.id})">
                            <i class="ri-delete-bin-line me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    addCard.insertAdjacentHTML('afterend', newCardHtml);

    const newCard = document.querySelector(`[data-attribute-id="${attribute.id}"]`);
    setTimeout(() => {
        newCard.style.transform = 'scale(1)';
        newCard.style.opacity = '1';
        newCard.style.transition = 'all 0.3s ease';
    }, 100);
}

// Update existing attribute card
function updateAttributeCard(attribute) {
    const card = document.querySelector(`[data-attribute-id="${attribute.id}"]`);
    if (!card) return;

    card.innerHTML = `
        <div class="attribute-card" onclick="flipCard(this)">
            <div class="card-front">
                <div class="text-center">
                    <div class="attribute-badge">Measurement Field</div>
                    <div class="attribute-name">${attribute.name}</div>
                    <div class="attribute-meta">
                        <i class="ri-scissors-line"></i>
                        <span>${attribute.service.name}</span>
                    </div>
                    <div class="attribute-meta mt-2">
                        <i class="ri-user-line"></i>
                        <span>${attribute.service.gender.charAt(0).toUpperCase() + attribute.service.gender.slice(1)}</span>
                    </div>
                    <small class="text-muted mt-3 d-block">Click to see options</small>
                </div>
            </div>
            <div class="card-back">
                <div class="text-center mb-3">
                    <div class="attribute-name mb-2">${attribute.name}</div>
                    <small class="text-muted d-block">${attribute.service.name}</small>
                    <small class="text-muted d-block">${attribute.service.gender.charAt(0).toUpperCase() + attribute.service.gender.slice(1)}</small>
                </div>
                <div class="card-actions">
                    <button class="btn-card btn-edit" onclick="event.stopPropagation(); editAttribute(${attribute.id})">
                        <i class="ri-edit-line me-1"></i>Edit
                    </button>
                    <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteAttribute(${attribute.id})">
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
