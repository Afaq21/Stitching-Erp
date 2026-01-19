@extends('layouts.master')

@section('title', 'Design Catalog')

@section('css')
<style>
/* Modern Card Styles - Professional Theme */
.design-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: #fff;
    color: #495057;
    cursor: pointer;
    position: relative;
    height: 280px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.design-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #405189;
}

.design-card.flipped {
    background: #f8f9fa;
    border-color: #405189;
}

.card-front, .card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    transition: transform 0.6s;
    display: flex;
    flex-direction: column;
}

.card-back {
    transform: rotateY(180deg);
    background: #f8f9fa;
    overflow-y: auto;
}

.design-card.flipped .card-front {
    transform: rotateY(180deg);
}

.design-card.flipped .card-back {
    transform: rotateY(0deg);
}

.design-image {
    height: 160px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.design-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.design-image i {
    font-size: 3rem;
    color: #dee2e6;
}

.design-info {
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.design-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.design-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.design-meta i {
    color: #405189;
}

.design-badge {
    display: inline-block;
    background: #405189;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1;
}

.card-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: auto;
    padding: 1rem;
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
.add-design-card {
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
    height: 280px;
    border-radius: 12px;
}

.add-design-card:hover {
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

/* Image Preview */
.image-preview {
    max-width: 200px;
    max-height: 150px;
    border-radius: 8px;
    margin-top: 0.5rem;
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
    .design-card {
        height: 260px;
    }

    .design-title {
        font-size: 0.95rem;
    }

    .card-actions {
        flex-direction: row;
        gap: 0.25rem;
        padding: 0.75rem;
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
                    <h2 class="mb-1">Design Catalog</h2>
                    <p class="text-muted mb-0">Manage design templates for services</p>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Search -->
                    <div class="search-container">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" class="form-control search-input" id="searchDesigns"
                               placeholder="Search designs..." value="{{ request('search') }}">
                    </div>

                    <!-- Add Design Button -->
                    <button class="btn btn-primary" onclick="openDesignModal()">
                        <i class="ri-add-line me-2"></i>Add Design
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Designs Grid -->
    <div class="row" id="designsGrid">
        <!-- Add Design Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="add-design-card" onclick="openDesignModal()">
                <i class="ri-add-circle-line add-icon"></i>
                <span class="fw-medium">Add New Design</span>
            </div>
        </div>

        <!-- Designs Cards -->
        @foreach($designs as $design)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-design-id="{{ $design->id }}">
                <div class="design-card" onclick="flipCard(this)">
                    <!-- Status Badge -->
                    <span class="badge {{ $design->is_active ? 'bg-success' : 'bg-secondary' }} status-badge">
                        {{ $design->is_active ? 'Active' : 'Inactive' }}
                    </span>

                    <!-- Front Side -->
                    <div class="card-front">
                        <div class="design-image">
                            @if($design->image_path)
                                <img src="{{ asset($design->image_path) }}" alt="{{ $design->title }}">
                            @else
                                <i class="ri-image-line"></i>
                            @endif
                        </div>
                        <div class="design-info">
                            <div class="design-title">{{ $design->title }}</div>
                            <div class="design-meta">
                                <i class="ri-scissors-line"></i>
                                <span>{{ $design->service->name ?? 'N/A' }}</span>
                            </div>
                            @if($design->price_adjustment > 0)
                                <div class="design-meta">
                                    <i class="ri-money-rupee-circle-line"></i>
                                    <span class="text-success">+Rs{{ number_format($design->price_adjustment, 0) }}</span>
                                </div>
                            @endif
                            <small class="text-muted mt-auto">Click to see options</small>
                        </div>
                    </div>

                    <!-- Back Side -->
                    <div class="card-back">
                        <div class="design-image">
                            @if($design->image_path)
                                <img src="{{ asset($design->image_path) }}" alt="{{ $design->title }}">
                            @else
                                <i class="ri-image-line"></i>
                            @endif
                        </div>
                        <div class="p-3">
                            <div class="design-title mb-2">{{ $design->title }}</div>
                            @if($design->description)
                                <p class="text-muted small mb-2">{{ Str::limit($design->description, 60) }}</p>
                            @endif
                        </div>
                        <div class="card-actions">
                            <button class="btn-card btn-edit" onclick="event.stopPropagation(); editDesign({{ $design->id }})">
                                <i class="ri-edit-line me-1"></i>Edit
                            </button>
                            <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteDesign({{ $design->id }})">
                                <i class="ri-delete-bin-line me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($designs->hasPages())
        <div class="pagination-container" id="paginationContainer">
            {{ $designs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Design Modal -->
<div class="modal fade" id="designModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="designModalTitle">Add New Design</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="designForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="designTitle" class="form-label">Design Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="designTitle" name="title" placeholder="e.g., Traditional Design" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="serviceId" class="form-label">Service <span class="text-danger">*</span></label>
                            <select class="form-select" id="serviceId" name="service_id" required>
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }} ({{ ucfirst($service->gender) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="designDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="designDescription" name="description" rows="3" placeholder="Design details..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="designImage" class="form-label">Design Image</label>
                            <input type="file" class="form-control" id="designImage" name="image" accept="image/*">
                            <small class="text-muted">Max: 2MB (JPEG, PNG, JPG, GIF)</small>
                            <div id="imagePreview"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="priceAdjustment" class="form-label">Price Adjustment (Rs)</label>
                            <input type="number" class="form-control" id="priceAdjustment" name="price_adjustment" min="0" step="0.01" value="0" placeholder="0">
                            <small class="text-muted">Extra charge for this design</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="isActive" name="is_active" value="1" checked>
                            <label class="form-check-label" for="isActive">
                                Active Design
                            </label>
                        </div>
                    </div>

                    <input type="hidden" id="designId" name="design_id">
                    <input type="hidden" id="currentImagePath" name="current_image_path">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-2"></i><span id="submitText">Add Design</span>
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

// Open Design Modal
function openDesignModal(designId = null) {
    const modal = new bootstrap.Modal(document.getElementById('designModal'));
    const form = document.getElementById('designForm');
    const title = document.getElementById('designModalTitle');
    const submitText = document.getElementById('submitText');

    // Reset form
    form.reset();
    document.getElementById('imagePreview').innerHTML = '';
    currentEditId = designId;

    if (designId) {
        // Edit mode
        title.textContent = 'Edit Design';
        submitText.textContent = 'Update Design';

        // Fetch design data
        fetch(`/Dashboard/design-catalog/${designId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('designTitle').value = data.title;
                document.getElementById('serviceId').value = data.service_id;
                document.getElementById('designDescription').value = data.description || '';
                document.getElementById('priceAdjustment').value = data.price_adjustment || 0;
                document.getElementById('isActive').checked = data.is_active;
                document.getElementById('designId').value = data.id;
                document.getElementById('currentImagePath').value = data.image_path || '';

                // Show current image
                if (data.image_path) {
                    document.getElementById('imagePreview').innerHTML = `
                        <img src="/${data.image_path}" class="image-preview img-thumbnail mt-2" alt="Current Image">
                        <p class="small text-muted mt-1">Current Image</p>
                    `;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showToast('Error loading design data', 'error');
            });
    } else {
        // Create mode
        title.textContent = 'Add New Design';
        submitText.textContent = 'Add Design';
        document.getElementById('designId').value = '';
        document.getElementById('currentImagePath').value = '';
    }

    modal.show();
}

// Edit Design
function editDesign(designId) {
    openDesignModal(designId);
}

// Delete Design
function deleteDesign(designId) {
    if (confirm('Are you sure you want to delete this design?')) {
        fetch(`/Dashboard/design-catalog/${designId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector(`[data-design-id="${designId}"]`);
                if (card) {
                    card.style.transform = 'scale(0)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                    }, 300);
                }
                showToast('Design deleted successfully!', 'success');
            } else {
                showToast('Error deleting design: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting design', 'error');
        });
    }
}

// Flip Card
function flipCard(card) {
    card.classList.toggle('flipped');
}

// Image Preview
document.getElementById('designImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" class="image-preview img-thumbnail mt-2" alt="Preview">
                <p class="small text-muted mt-1">New Image Preview</p>
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Form Submission
document.getElementById('designForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const designId = document.getElementById('designId').value;

    let url = '/Dashboard/design-catalog';
    let method = 'POST';

    if (designId) {
        url = `/Dashboard/design-catalog/${designId}`;
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
            bootstrap.Modal.getInstance(document.getElementById('designModal')).hide();

            if (designId) {
                updateDesignCard(data.design);
                showToast('Design updated successfully!', 'success');
            } else {
                addDesignCard(data.design);
                showToast('Design added successfully!', 'success');
            }
        } else {
            showToast('Error: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('Error saving design', 'error');
    });
});

// Search Functionality with AJAX
let searchTimeout;
document.getElementById('searchDesigns').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadDesigns(e.target.value);
    }, 500);
});

// Load designs via AJAX
function loadDesigns(search = '') {
    const url = new URL(window.location.origin + '/Dashboard/design-catalog');
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
        const newGrid = doc.querySelector('#designsGrid');
        const newPagination = doc.querySelector('#paginationContainer');

        if (newGrid) {
            document.getElementById('designsGrid').innerHTML = newGrid.innerHTML;
        }
        if (newPagination) {
            document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error loading designs:', error);
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
                const newGrid = doc.querySelector('#designsGrid');
                const newPagination = doc.querySelector('#paginationContainer');

                if (newGrid) {
                    document.getElementById('designsGrid').innerHTML = newGrid.innerHTML;
                }
                if (newPagination) {
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                }

                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }
});

// Add new design card to grid
function addDesignCard(design) {
    const designsGrid = document.getElementById('designsGrid');
    const addCard = designsGrid.querySelector('.add-design-card').parentElement;

    const imagePath = design.image_path ? `/${design.image_path}` : '';
    const imageHtml = imagePath ? 
        `<img src="${imagePath}" alt="${design.title}">` : 
        `<i class="ri-image-line"></i>`;

    const priceHtml = design.price_adjustment > 0 ? `
        <div class="design-meta">
            <i class="ri-money-rupee-circle-line"></i>
            <span class="text-success">+Rs${Number(design.price_adjustment).toLocaleString()}</span>
        </div>
    ` : '';

    const descriptionHtml = design.description ? 
        `<p class="text-muted small mb-2">${design.description.substring(0, 60)}${design.description.length > 60 ? '...' : ''}</p>` : '';

    const newCardHtml = `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-design-id="${design.id}" style="transform: scale(0); opacity: 0;">
            <div class="design-card" onclick="flipCard(this)">
                <span class="badge ${design.is_active ? 'bg-success' : 'bg-secondary'} status-badge">
                    ${design.is_active ? 'Active' : 'Inactive'}
                </span>
                <div class="card-front">
                    <div class="design-image">
                        ${imageHtml}
                    </div>
                    <div class="design-info">
                        <div class="design-title">${design.title}</div>
                        <div class="design-meta">
                            <i class="ri-scissors-line"></i>
                            <span>${design.service.name}</span>
                        </div>
                        ${priceHtml}
                        <small class="text-muted mt-auto">Click to see options</small>
                    </div>
                </div>
                <div class="card-back">
                    <div class="design-image">
                        ${imageHtml}
                    </div>
                    <div class="p-3">
                        <div class="design-title mb-2">${design.title}</div>
                        ${descriptionHtml}
                    </div>
                    <div class="card-actions">
                        <button class="btn-card btn-edit" onclick="event.stopPropagation(); editDesign(${design.id})">
                            <i class="ri-edit-line me-1"></i>Edit
                        </button>
                        <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteDesign(${design.id})">
                            <i class="ri-delete-bin-line me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    addCard.insertAdjacentHTML('afterend', newCardHtml);

    const newCard = document.querySelector(`[data-design-id="${design.id}"]`);
    setTimeout(() => {
        newCard.style.transform = 'scale(1)';
        newCard.style.opacity = '1';
        newCard.style.transition = 'all 0.3s ease';
    }, 100);
}

// Update existing design card
function updateDesignCard(design) {
    const card = document.querySelector(`[data-design-id="${design.id}"]`);
    if (!card) return;

    const imagePath = design.image_path ? `/${design.image_path}` : '';
    const imageHtml = imagePath ? 
        `<img src="${imagePath}" alt="${design.title}">` : 
        `<i class="ri-image-line"></i>`;

    const priceHtml = design.price_adjustment > 0 ? `
        <div class="design-meta">
            <i class="ri-money-rupee-circle-line"></i>
            <span class="text-success">+Rs${Number(design.price_adjustment).toLocaleString()}</span>
        </div>
    ` : '';

    const descriptionHtml = design.description ? 
        `<p class="text-muted small mb-2">${design.description.substring(0, 60)}${design.description.length > 60 ? '...' : ''}</p>` : '';

    card.innerHTML = `
        <div class="design-card" onclick="flipCard(this)">
            <span class="badge ${design.is_active ? 'bg-success' : 'bg-secondary'} status-badge">
                ${design.is_active ? 'Active' : 'Inactive'}
            </span>
            <div class="card-front">
                <div class="design-image">
                    ${imageHtml}
                </div>
                <div class="design-info">
                    <div class="design-title">${design.title}</div>
                    <div class="design-meta">
                        <i class="ri-scissors-line"></i>
                        <span>${design.service.name}</span>
                    </div>
                    ${priceHtml}
                    <small class="text-muted mt-auto">Click to see options</small>
                </div>
            </div>
            <div class="card-back">
                <div class="design-image">
                    ${imageHtml}
                </div>
                <div class="p-3">
                    <div class="design-title mb-2">${design.title}</div>
                    ${descriptionHtml}
                </div>
                <div class="card-actions">
                    <button class="btn-card btn-edit" onclick="event.stopPropagation(); editDesign(${design.id})">
                        <i class="ri-edit-line me-1"></i>Edit
                    </button>
                    <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteDesign(${design.id})">
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
