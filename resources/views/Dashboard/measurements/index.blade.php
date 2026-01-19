@extends('layouts.master')

@section('title', 'Customer Measurements')

@section('css')
<style>
/* Modern Card Styles - Professional Theme */
.measurement-card {
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

.measurement-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #405189;
}

.measurement-card.flipped {
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
    overflow-y: auto;
}

.measurement-card.flipped .card-front {
    transform: rotateY(180deg);
}

.measurement-card.flipped .card-back {
    transform: rotateY(0deg);
}

.measurement-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.measurement-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #6c757d;
}

.measurement-meta i {
    color: #405189;
}

.measurement-badge {
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
    margin-top: 1rem;
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
.add-measurement-card {
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

.add-measurement-card:hover {
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

.measurement-values {
    max-height: 120px;
    overflow-y: auto;
    font-size: 0.85rem;
}

.measurement-values::-webkit-scrollbar {
    width: 4px;
}

.measurement-values::-webkit-scrollbar-thumb {
    background: #405189;
    border-radius: 4px;
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
    .measurement-card {
        height: 200px;
    }

    .measurement-name {
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

.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
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
                    <h2 class="mb-1">Customer Measurements</h2>
                    <p class="text-muted mb-0">Manage customer measurement records</p>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Search -->
                    <div class="search-container">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" class="form-control search-input" id="searchMeasurements"
                            placeholder="Search measurements..." value="{{ request('search') }}">
                    </div>

                    <!-- Add Measurement Button -->
                    <button class="btn btn-primary" onclick="openMeasurementModal()">
                        <i class="ri-add-line me-2"></i>Add Measurement
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Measurements Grid -->
    <div class="row" id="measurementsGrid">
        <!-- Add Measurement Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="add-measurement-card" onclick="openMeasurementModal()">
                <i class="ri-add-circle-line add-icon"></i>
                <span class="fw-medium">Add New Measurement</span>
            </div>
        </div>

        <!-- Measurements Cards -->
        @foreach($measurements as $measurement)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-measurement-id="{{ $measurement->id }}">
                <div class="measurement-card" onclick="flipCard(this)">

                    <!-- Front Side -->
                    <div class="card-front">
                        <div class="text-center">
                            <div class="measurement-badge">{{ $measurement->service->name ?? 'N/A' }}</div>
                            <div class="measurement-name">{{ $measurement->customer->name ?? 'N/A' }}</div>
                            <div class="measurement-meta">
                                <i class="ri-phone-line"></i>
                                <span>{{ $measurement->customer->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="measurement-meta mt-2">
                                <i class="ri-ruler-line"></i>
                                <span>{{ $measurement->values->count() }} measurements</span>
                            </div>
                            <small class="text-muted mt-3 d-block">Click to see details</small>
                        </div>
                    </div>

                    <!-- Back Side -->
                    <div class="card-back">
                        <div class="text-center w-100">
                            <div class="measurement-name mb-2">{{ $measurement->service->name ?? 'N/A' }}</div>

                            @if($measurement->values && $measurement->values->count() > 0)
                                <div class="measurement-values text-start w-100 mb-2">
                                    @foreach($measurement->values->take(3) as $val)
                                        <div class="mb-1">
                                            <strong>{{ $val->measurementAttribute->name ?? 'N/A' }}:</strong> {{ $val->value }}"
                                        </div>
                                    @endforeach
                                    @if($measurement->values->count() > 3)
                                        <small class="text-muted">+{{ $measurement->values->count() - 3 }} more...</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="card-actions">
                            <button class="btn-card btn-edit" onclick="event.stopPropagation(); editMeasurement({{ $measurement->id }})">
                                <i class="ri-edit-line me-1"></i>Edit
                            </button>
                            <button class="btn-card btn-delete" onclick="event.stopPropagation(); deleteMeasurement({{ $measurement->id }})">
                                <i class="ri-delete-bin-line me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($measurements->hasPages())
        <div class="pagination-container" id="paginationContainer">
            {{ $measurements->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Measurement Modal -->
<div class="modal fade" id="measurementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="measurementModalTitle">Add New Measurement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="measurementForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select" id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
                            <select class="form-select" id="service_id" name="service_id" required>
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }} ({{ ucfirst($service->gender) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Measurement Fields -->
                    <div id="measurementFieldsContainer">
                        <div class="alert alert-info">
                            <i class="ri-information-line"></i> Please select a service to load measurement fields
                        </div>
                    </div>

                    <input type="hidden" id="measurementId" name="measurement_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-2"></i><span id="submitText">Save Measurement</span>
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

// Open Measurement Modal
function openMeasurementModal(measurementId = null) {
    const modal = new bootstrap.Modal(document.getElementById('measurementModal'));
    const form = document.getElementById('measurementForm');
    const title = document.getElementById('measurementModalTitle');
    const submitText = document.getElementById('submitText');
    const serviceSelect = document.getElementById('service_id');
    const customerSelect = document.getElementById('customer_id');

    // Reset form
    form.reset();
    currentEditId = measurementId;

    // Remove any existing disabled note
    const existingNote = document.querySelector('.service-disabled-note');
    if (existingNote) existingNote.remove();

    // Enable service and customer selects (for create mode)
    serviceSelect.disabled = false;
    customerSelect.disabled = false;
    serviceSelect.style.backgroundColor = '';
    customerSelect.style.backgroundColor = '';
    serviceSelect.style.cursor = '';
    customerSelect.style.cursor = '';

    if (measurementId) {
        // Edit mode - Show loading message instead of info message
        document.getElementById('measurementFieldsContainer').innerHTML = '<div class="text-center"><i class="ri-loader-4-line spin"></i> Loading measurement fields...</div>';
        
        title.textContent = 'Edit Measurement';
        submitText.textContent = 'Update Measurement';
        document.getElementById('measurementId').value = measurementId;

        // Fetch measurement data
        fetch(`/Dashboard/measurements/${measurementId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('customer_id').value = data.customer_id;
                document.getElementById('service_id').value = data.service_id;

                // Disable customer and service selection in edit mode
                customerSelect.disabled = true;
                serviceSelect.disabled = true;
                customerSelect.style.backgroundColor = '#e9ecef';
                serviceSelect.style.backgroundColor = '#e9ecef';
                customerSelect.style.cursor = 'not-allowed';
                serviceSelect.style.cursor = 'not-allowed';

                // Add informational note
                const serviceContainer = serviceSelect.parentElement;
                const note = document.createElement('small');
                note.className = 'text-muted d-block mt-1 service-disabled-note';
                note.innerHTML = '<i class="ri-information-line"></i> Customer and Service cannot be changed in edit mode. Delete and create new if needed.';
                serviceContainer.appendChild(note);

                // Trigger service change to load fields
                document.getElementById('service_id').dispatchEvent(new Event('change'));

                // Wait for fields to load, then populate values
                setTimeout(() => {
                    if (data.values) {
                        Object.entries(data.values).forEach(([attrId, value]) => {
                            const input = document.querySelector(`input[name="values[${attrId}]"]`);
                            if (input) input.value = value;
                        });
                    }
                }, 500);
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showToast('Error loading measurement data', 'error');
            });
    } else {
        // Create mode - Show info message only in create mode
        document.getElementById('measurementFieldsContainer').innerHTML = '<div class="alert alert-info"><i class="ri-information-line"></i> Please select a service to load measurement fields</div>';
        
        title.textContent = 'Add New Measurement';
        submitText.textContent = 'Save Measurement';
        document.getElementById('measurementId').value = '';
    }

    modal.show();
}

// Edit Measurement
function editMeasurement(measurementId) {
    openMeasurementModal(measurementId);
}

// Delete Measurement
function deleteMeasurement(measurementId) {
    if (confirm('Are you sure you want to delete this measurement?')) {
        fetch(`/Dashboard/measurements/${measurementId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector(`[data-measurement-id="${measurementId}"]`);
                if (card) {
                    card.style.transform = 'scale(0)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                    }, 300);
                }
                showToast('Measurement deleted successfully!', 'success');
            } else {
                showToast('Error deleting measurement: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting measurement', 'error');
        });
    }
}

// Flip Card
function flipCard(card) {
    card.classList.toggle('flipped');
}

// Load measurement fields when service is selected
document.getElementById('service_id').addEventListener('change', function() {
    const serviceId = this.value;
    const container = document.getElementById('measurementFieldsContainer');

    if (!serviceId) {
        container.innerHTML = '<div class="alert alert-info"><i class="ri-information-line"></i> Please select a service to load measurement fields</div>';
        return;
    }

    container.innerHTML = '<div class="text-center"><i class="ri-loader-4-line spin"></i> Loading measurement fields...</div>';

    fetch(`/api/services/${serviceId}/measurements`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.measurements.length > 0) {
                let html = '<div class="row">';
                data.measurements.forEach(measurement => {
                    html += `
                        <div class="col-md-6 mb-3">
                            <label class="form-label">${measurement.name}</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       name="values[${measurement.id}]"
                                       placeholder="Enter ${measurement.name}"
                                       required>
                                <span class="input-group-text">inch</span>
                            </div>
                            <textarea class="form-control mt-2"
                                      name="notes[${measurement.id}]"
                                      placeholder="Notes for ${measurement.name}"
                                      rows="2"></textarea>
                        </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="alert alert-warning"><i class="ri-alert-line"></i> No measurement attributes defined for this service</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="alert alert-danger"><i class="ri-error-warning-line"></i> Error loading measurement fields</div>';
        });
});

// Form Submission
document.getElementById('measurementForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const measurementId = document.getElementById('measurementId').value;

    let url = '/Dashboard/measurements';
    let method = 'POST';

    if (measurementId) {
        url = `/Dashboard/measurements/${measurementId}`;
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
            bootstrap.Modal.getInstance(document.getElementById('measurementModal')).hide();
            showToast(data.message || 'Measurement saved successfully!', 'success');

            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast('Error: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('Error saving measurement', 'error');
    });
});

// Search Functionality with AJAX
let searchTimeout;
document.getElementById('searchMeasurements').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadMeasurements(e.target.value);
    }, 500);
});

// Load measurements via AJAX
function loadMeasurements(search = '') {
    const url = new URL(window.location.origin + '/Dashboard/measurements');
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
        const newGrid = doc.querySelector('#measurementsGrid');
        const newPagination = doc.querySelector('#paginationContainer');

        if (newGrid) {
            document.getElementById('measurementsGrid').innerHTML = newGrid.innerHTML;
        }
        if (newPagination) {
            document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error loading measurements:', error);
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
                const newGrid = doc.querySelector('#measurementsGrid');
                const newPagination = doc.querySelector('#paginationContainer');

                if (newGrid) {
                    document.getElementById('measurementsGrid').innerHTML = newGrid.innerHTML;
                }
                if (newPagination) {
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                }

                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }
});

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
