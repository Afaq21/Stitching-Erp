@extends('layouts.master')

@section('css')
<style>
    /* Hide ALL steps by default - step persistence will handle visibility */
    .step-content {
        display: none !important;
    }
    
    /* Only show active step */
    .step-content.active {
        display: block !important;
    }
    
    .wizard-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 10px;
        position: relative;
        transition: all 0.2s ease;
    }

    .step.active {
        background: white;
        color: #667eea;
        transform: scale(1.1);
    }

    .step.completed {
        background: #28a745;
        color: white;
    }

    .step::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 100%;
        width: 20px;
        height: 2px;
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-50%);
    }

    .step:last-child::after {
        display: none;
    }

    .form-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
    }

    .step-content {
        display: none;
        animation: fadeIn 0.2s ease-in-out;
    }

    .step-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .service-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .service-card:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
    }

    .service-card.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .design-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .design-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .design-card.selected {
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.3);
    }

    .btn-wizard {
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-wizard:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .success-icon {
        animation: successPulse 2s ease-in-out infinite;
    }

    @keyframes successPulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }

    .booking-summary {
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
</style>
@endsection

@section('content')
<!-- Instant Step Restoration - Execute BEFORE page renders -->
<script>
(function() {
    const savedStep = localStorage.getItem('booking_step');
    if (savedStep && savedStep !== '1') {
        const stepNumber = parseInt(savedStep);
        if (stepNumber >= 2 && stepNumber <= 7) {
            // Inject CSS immediately to show ONLY the saved step
            const style = document.createElement('style');
            style.id = 'instant-step-fix';
            style.textContent = `
                /* Show ONLY the saved step immediately */
                .step-content#step${stepNumber} { display: block !important; }
                
                /* Update step indicators immediately */
                .step { 
                    background: rgba(255, 255, 255, 0.2) !important; 
                    color: white !important; 
                    transform: scale(1) !important; 
                }
                
                /* Active step indicator */
                .step[data-step="${stepNumber}"] { 
                    background: white !important; 
                    color: #667eea !important; 
                    transform: scale(1.1) !important; 
                }
            `;
            
            // Mark previous steps as completed
            for (let i = 1; i < stepNumber; i++) {
                style.textContent += `
                    .step[data-step="${i}"] { 
                        background: #28a745 !important; 
                        color: white !important; 
                    }
                `;
            }
            
            document.head.appendChild(style);
        }
    } else {
        // No saved state - show step 1
        const style = document.createElement('style');
        style.id = 'instant-step-fix';
        style.textContent = `
            .step-content#step1 { display: block !important; }
        `;
        document.head.appendChild(style);
    }
})();
</script>

<div class="page-content">
    <!-- Wizard Header -->
    <div class="wizard-container">
        <div class="text-center mb-4">
            <h3 class="mb-2">Create New Booking</h3>
            <p class="mb-0 opacity-75">Follow the steps to create a new customer booking</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step" data-step="1" id="step-indicator-1">
                <i class="ri-user-line"></i>
            </div>
            <div class="step" data-step="2">
                <i class="ri-scissors-line"></i>
            </div>
            <div class="step" data-step="3">
                <i class="ri-ruler-line"></i>
            </div>
            <div class="step" data-step="4">
                <i class="ri-palette-line"></i>
            </div>
            <div class="step" data-step="5">
                <i class="ri-calendar-line"></i>
            </div>
            <div class="step" data-step="6">
                Rs
            </div>
            <div class="step" data-step="7">
                <i class="ri-check-line"></i>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card form-card">
                <form id="bookingForm" method="POST" action="{{ route('Dashboard.bookings.store') }}">
                    @csrf

                    <!-- Step 1: Customer Selection -->
                    <div class="step-content" id="step1">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-user-line me-2 text-primary"></i>Select Customer
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="form-label">Choose Customer</label>
                                    <select name="customer_id" id="customerSelect" class="form-select form-select-lg" required>
                                        <option value="">Select a customer...</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}" data-address="{{ $customer->address }}">
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
                                        <i class="ri-add-line me-1"></i>New Customer
                                    </button>
                                </div>
                            </div>

                            <!-- Customer Info Display -->
                            <div id="customerInfo" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h6 class="mb-1">Customer Details:</h6>
                                    <p class="mb-1"><strong>Phone:</strong> <span id="customerPhone"></span></p>
                                    <p class="mb-0"><strong>Address:</strong> <span id="customerAddress"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Service Selection -->
                    <div class="step-content" id="step2">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-scissors-line me-2 text-primary"></i>Select Services
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                @foreach($services as $service)
                                <div class="col-md-6 mb-3">
                                    <div class="service-card" onclick="toggleService({{ $service->id }}, '{{ $service->name }}', {{ $service->price }})">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" class="d-none service-checkbox" id="service_{{ $service->id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $service->name }}</h6>
                                                <small class="text-muted">{{ ucfirst($service->gender) }} • {{ ucfirst($service->service_category) }}</small>
                                            </div>
                                            <div class="text-end">
                                                <strong>Rs{{ number_format($service->price, 0) }}</strong>
                                                <div class="service-check-icon" style="display: none;">
                                                    <i class="ri-check-line text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Selected Services Summary -->
                            <div id="selectedServicesSummary" style="display: none;" class="mt-4">
                                <h6 class="mb-3">Selected Services:</h6>
                                <div id="selectedServicesList" class="row"></div>
                                <div class="mt-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between">
                                        <strong>Total Amount:</strong>
                                        <strong id="totalServicesAmount" class="text-success">Rs0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Measurements -->
                    <div class="step-content" id="step3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-ruler-line me-2 text-primary"></i>Customer Measurements
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div id="measurementsContainer">
                                <div class="text-center text-muted">
                                    <p>Please select customer and services first to load measurements</p>
                                </div>
                            </div>

                            <!-- Multiple Services Measurements -->
                            <div id="multipleServicesMeasurements" style="display: none;">
                                <div class="accordion" id="measurementsAccordion"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Design Selection -->
                    <div class="step-content" id="step4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-palette-line me-2 text-primary"></i>Choose Design (Optional)
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    Select designs for each service individually
                                </div>
                            </div>

                            <!-- Multiple Services Designs -->
                            <div id="multipleServicesDesigns" style="display: none;">
                                <div class="accordion" id="designsAccordion"></div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="button" class="btn btn-outline-secondary me-2" onclick="skipAllDesigns()">
                                    <i class="ri-skip-forward-line me-1"></i>Skip All Designs
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Dates & Priority -->
                    <div class="step-content" id="step5">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-calendar-line me-2 text-primary"></i>Schedule & Priority
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Booking Date</label>
                                    <input type="date" name="booking_date" class="form-control form-control-lg"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Delivery Date</label>
                                    <input type="date" name="delivery_date" class="form-control form-control-lg"
                                        min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select form-select-lg">
                                        <option value="">Normal Priority</option>
                                        <option value="low">Low Priority</option>
                                        <option value="medium">Medium Priority</option>
                                        <option value="high">High Priority</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select form-select-lg" required>
                                        @foreach($statuses as $key => $status)
                                        <option value="{{ $key }}" {{ $key == 'pending' ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="3"
                                    placeholder="Any special instructions or notes..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 6: Payment Details -->
                    <div class="step-content" id="step6">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-money-rupee-circle-line me-2 text-primary"></i>Payment Details
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Total Amount</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">Rs</span>
                                        <input type="number" name="total_amount" id="totalAmount"
                                            class="form-control" min="0" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Advance Payment</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">Rs</span>
                                        <input type="number" name="advance_amount" id="advanceAmount"
                                            class="form-control" min="0" step="0.01" value="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Summary -->
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="mb-3">Payment Summary</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Amount:</span>
                                    <strong id="summaryTotal">Rs</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Advance Payment:</span>
                                    <span id="summaryAdvance">Rs</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Remaining Amount:</strong>
                                    <strong class="text-warning" id="summaryRemaining">Rs</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 7: Success & Invoice -->
                    <div class="step-content" id="step7">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="ri-check-line me-2"></i>Booking Created Successfully!
                            </h5>
                        </div>
                        <div class="card-body p-5 text-center">
                            <div class="mb-4">
                                <div class="success-icon mb-3">
                                    <i class="ri-check-double-line text-success" style="font-size: 4rem;"></i>
                                </div>
                                <h3 class="text-success mb-3">Thank You!</h3>
                                <p class="lead mb-4">Your booking has been created successfully. We appreciate your business!</p>
                            </div>

                            <!-- Booking Summary -->
                            <div class="booking-summary bg-light p-4 rounded mb-4" id="bookingSummary" style="display: none;">
                                <h6 class="mb-3">Booking Summary</h6>
                                <div class="row text-start">
                                    <div class="col-md-6">
                                        <p><strong>Booking ID:</strong> <span id="summaryBookingId">#000000</span></p>
                                        <p><strong>Customer:</strong> <span id="summaryCustomerName">-</span></p>
                                        <p><strong>Services:</strong> <span id="summaryServiceName">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Booking Date:</strong> <span id="summaryBookingDate">-</span></p>
                                        <p><strong>Delivery Date:</strong> <span id="summaryDeliveryDate">-</span></p>
                                        <p><strong>Total Amount:</strong> <span id="summaryTotalAmount" class="text-success">Rs0</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-center gap-3">
                                <button type="button" class="btn btn-success btn-lg" id="downloadInvoiceBtn" onclick="downloadInvoice()" disabled>
                                    <i class="ri-file-pdf-line me-2"></i>Download Invoice
                                </button>
                                <a href="{{ route('Dashboard.bookings.index') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="ri-list-check me-2"></i>View All Bookings
                                </a>
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="createNewBooking()">
                                    <i class="ri-add-line me-2"></i>Create New Booking
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="manualClearStorage()" title="Clear Storage">
                                    <i class="ri-delete-bin-line me-1"></i>Clear Storage
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="card-footer bg-white border-0 p-4">
                        <div class="d-flex justify-content-between">
                            <button type="button" id="prevBtn" class="btn btn-outline-secondary btn-wizard" onclick="changeStep(-1)" style="display: none;">
                                <i class="ri-arrow-left-line me-1"></i>Previous
                            </button>
                            <div class="ms-auto">
                                <button type="button" id="nextBtn" class="btn btn-primary btn-wizard" onclick="changeStep(1)">
                                    Next<i class="ri-arrow-right-line ms-1"></i>
                                </button>
                                <button type="submit" id="submitBtn" class="btn btn-success btn-wizard" style="display: none;">
                                    <i class="ri-check-line me-1"></i>Create Booking
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- New Customer Modal -->
<div class="modal fade" id="newCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="newCustomerForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Global Data for JavaScript -->
<script>
    window.appData = {
        designs: {!! json_encode($designs) !!},
        services: {!! json_encode($services) !!},
        customers: {!! json_encode($customers) !!}
    };
</script>

<script>
    let currentStep = 1;
    const totalSteps = 7;
    let selectedCustomerId = null;
    let selectedServices = [];
    let createdBookingId = null;
    let isInitialized = false;

    // Simple step persistence
    function saveCurrentState() {
        localStorage.setItem('booking_step', currentStep);
        localStorage.setItem('booking_customer', selectedCustomerId || '');
        localStorage.setItem('booking_services', JSON.stringify(selectedServices));
    }

    function loadSavedState() {
        const savedStep = localStorage.getItem('booking_step');
        const savedCustomer = localStorage.getItem('booking_customer');
        const savedServices = localStorage.getItem('booking_services');

        if (savedStep) {
            currentStep = parseInt(savedStep);
            selectedCustomerId = savedCustomer || null;
            try {
                selectedServices = JSON.parse(savedServices) || [];
            } catch (e) {
                selectedServices = [];
            }
            return true;
        }
        return false;
    }

    function clearSavedState() {
        console.log('Clearing localStorage...');
        localStorage.removeItem('booking_step');
        localStorage.removeItem('booking_customer');
        localStorage.removeItem('booking_services');
        
        // Force clear any other booking-related items
        const keys = Object.keys(localStorage);
        keys.forEach(key => {
            if (key.startsWith('booking_')) {
                localStorage.removeItem(key);
            }
        });
        
        console.log('localStorage cleared successfully');
    }

    // Initialize immediately when script loads
    function initializeBookingForm() {
        if (isInitialized) return;
        
        const hasState = loadSavedState();
        
        // Remove the instant fix CSS since we're taking over
        const instantFix = document.getElementById('instant-step-fix');
        if (instantFix) instantFix.remove();
        
        // Hide all steps first
        for (let i = 1; i <= totalSteps; i++) {
            const stepContent = document.getElementById(`step${i}`);
            const stepIndicator = document.querySelector(`.step[data-step="${i}"]`);
            
            if (stepContent) stepContent.classList.remove('active');
            if (stepIndicator) {
                stepIndicator.classList.remove('active', 'completed');
            }
        }
        
        // Show current step (either saved or step 1)
        const currentStepContent = document.getElementById(`step${currentStep}`);
        const currentStepIndicator = document.querySelector(`.step[data-step="${currentStep}"]`);
        
        if (currentStepContent) currentStepContent.classList.add('active');
        if (currentStepIndicator) currentStepIndicator.classList.add('active');
        
        // Mark previous steps as completed
        for (let i = 1; i < currentStep; i++) {
            const stepIndicator = document.querySelector(`.step[data-step="${i}"]`);
            if (stepIndicator) stepIndicator.classList.add('completed');
        }
        
        // Restore form data if we have saved state
        if (hasState && currentStep > 1) {
            setTimeout(() => restoreFormData(), 50);
        }
        
        updateButtons();
        isInitialized = true;
    }

    function restoreFormData() {
        // Restore customer selection
        if (selectedCustomerId) {
            const customerSelect = document.getElementById('customerSelect');
            if (customerSelect) {
                customerSelect.value = selectedCustomerId;
                customerSelect.dispatchEvent(new Event('change'));
            }
        }

        // Restore service selections
        if (selectedServices && selectedServices.length > 0) {
            selectedServices.forEach(service => {
                const checkbox = document.getElementById(`service_${service.id}`);
                if (checkbox) {
                    checkbox.checked = true;
                    const serviceCard = checkbox.closest('.service-card');
                    if (serviceCard) {
                        serviceCard.classList.add('selected');
                        const checkIcon = serviceCard.querySelector('.service-check-icon');
                        if (checkIcon) checkIcon.style.display = 'block';
                    }
                }
            });
            
            updateSelectedServicesSummary();
            updateTotalAmount();
        }
        
        // Load step-specific content immediately for current step
        if (currentStep === 3 && selectedCustomerId && selectedServices.length > 0) {
            console.log('Restoring step 3: Loading measurements');
            loadCustomerMeasurements();
        }
        if (currentStep === 4 && selectedServices.length > 0) {
            console.log('Restoring step 4: Loading designs');
            loadMultipleServicesDesigns();
        }
    }

    // Initialize as soon as possible
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initializeBookingForm();
        });
    } else {
        initializeBookingForm();
    }

    // Additional initialization for step-specific content
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure proper initialization after DOM is ready
        setTimeout(() => {
            if (!isInitialized) {
                initializeBookingForm();
            }
        }, 100);
    });

    // Step navigation - Simple and fast
    function changeStep(direction) {
        if (direction === 1 && !validateCurrentStep()) {
            return;
        }

        const newStep = currentStep + direction;
        if (newStep >= 1 && newStep <= totalSteps) {
            // Hide current step
            const currentStepElement = document.getElementById(`step${currentStep}`);
            const currentStepIndicator = document.querySelector(`.step[data-step="${currentStep}"]`);
            
            if (currentStepElement) currentStepElement.classList.remove('active');
            if (currentStepIndicator) currentStepIndicator.classList.remove('active');

            // Mark as completed if moving forward
            if (direction === 1 && currentStepIndicator) {
                currentStepIndicator.classList.add('completed');
            }

            // Show new step
            currentStep = newStep;
            const newStepElement = document.getElementById(`step${currentStep}`);
            const newStepIndicator = document.querySelector(`.step[data-step="${currentStep}"]`);
            
            if (newStepElement) newStepElement.classList.add('active');
            if (newStepIndicator) newStepIndicator.classList.add('active');

            // Update buttons and save state
            updateButtons();
            saveCurrentState();

            // Load step-specific data immediately when navigating forward
            if (direction === 1) {
                if (currentStep === 3 && selectedCustomerId && selectedServices.length > 0) {
                    loadCustomerMeasurements();
                }
                if (currentStep === 4 && selectedServices.length > 0) {
                    loadMultipleServicesDesigns();
                }
            }
        }
    }

    function updateButtons() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        if (prevBtn) {
            prevBtn.style.display = currentStep === 1 || currentStep === 7 ? 'none' : 'block';
        }
        if (nextBtn) {
            nextBtn.style.display = currentStep === 6 || currentStep === 7 ? 'none' : 'block';
        }
        if (submitBtn) {
            submitBtn.style.display = currentStep === 6 ? 'block' : 'none';
        }
    }

    function validateCurrentStep() {
        switch (currentStep) {
            case 1:
                return document.getElementById('customerSelect').value !== '';
            case 2:
                return selectedServices.length > 0;
            case 3:
                // Measurements step - always allow to proceed
                return true;
            case 4:
                // Design step - optional, always allow to proceed
                return true;
            case 5:
                const bookingDate = document.querySelector('input[name="booking_date"]').value;
                const deliveryDate = document.querySelector('input[name="delivery_date"]').value;
                return bookingDate && deliveryDate;
            case 6:
                return document.getElementById('totalAmount').value > 0;
            case 7:
                // Success step - no validation needed
                return true;
            default:
                return true;
        }
    }

    // Customer selection
    document.getElementById('customerSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const customerInfo = document.getElementById('customerInfo');

        selectedCustomerId = this.value;

        if (this.value) {
            document.getElementById('customerPhone').textContent = selectedOption.dataset.phone || 'N/A';
            document.getElementById('customerAddress').textContent = selectedOption.dataset.address || 'N/A';
            customerInfo.style.display = 'block';
        } else {
            customerInfo.style.display = 'none';
            selectedCustomerId = null;
        }
        
        saveCurrentState();
    });

    // Service selection
    // Service selection (multiple)
    function toggleService(serviceId, serviceName, servicePrice) {
        const checkbox = document.getElementById(`service_${serviceId}`);
        const serviceCard = checkbox.closest('.service-card');
        const checkIcon = serviceCard.querySelector('.service-check-icon');

        if (checkbox.checked) {
            // Unselect service
            checkbox.checked = false;
            serviceCard.classList.remove('selected');
            checkIcon.style.display = 'none';

            // Remove from selectedServices array
            selectedServices = selectedServices.filter(service => service.id !== serviceId);
        } else {
            // Select service
            checkbox.checked = true;
            serviceCard.classList.add('selected');
            checkIcon.style.display = 'block';

            // Add to selectedServices array
            selectedServices.push({
                id: serviceId,
                name: serviceName,
                price: servicePrice
            });
        }

        updateSelectedServicesSummary();
        updateTotalAmount();
        
        // Save state when services are selected/deselected
        saveCurrentState();
    }

    function updateSelectedServicesSummary() {
        const summaryDiv = document.getElementById('selectedServicesSummary');
        const listDiv = document.getElementById('selectedServicesList');
        const totalDiv = document.getElementById('totalServicesAmount');

        if (selectedServices.length === 0) {
            summaryDiv.style.display = 'none';
            return;
        }

        let html = '';
        let totalAmount = 0;

        selectedServices.forEach(service => {
            totalAmount += parseFloat(service.price);
            html += `
                <div class="col-md-6 mb-2">
                    <div class="card border-success">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between">
                                <span class="small">${service.name}</span>
                                <strong class="small text-success">Rs${Number(service.price).toLocaleString()}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        listDiv.innerHTML = html;
        totalDiv.textContent = `Rs${totalAmount.toLocaleString()}`;
        summaryDiv.style.display = 'block';
    }

    function updateTotalAmount() {
        const totalAmount = selectedServices.reduce((sum, service) => sum + parseFloat(service.price), 0);
        document.getElementById('totalAmount').value = totalAmount;
        updatePaymentSummary();
    }

    // Load designs based on service
    function loadDesigns(serviceId) {
        const designs = window.appData.designs;
        const serviceDesigns = designs.filter(design => design.service_id == serviceId);
        const container = document.getElementById('designContainer');

        // Hide the design next button initially
        document.getElementById('designNextBtn').style.display = 'none';

        if (serviceDesigns.length === 0) {
            container.innerHTML = '<div class="col-12 text-center text-muted"><p>No designs available for this service</p></div>';
            return;
        }

        let html = '';
        serviceDesigns.forEach(design => {
            html += `
            <div class="col-md-4 mb-3">
                <div class="design-card" onclick="selectDesign(${design.id})">
                    <input type="radio" name="design_catalog_id" value="${design.id}" class="d-none">
                    ${design.image_path ?
                        `<img src="/${design.image_path}" class="card-img-top" style="height: 150px; object-fit: cover;">` :
                        `<div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                            <i class="ri-image-line text-muted" style="font-size: 2rem;"></i>
                        </div>`
                    }
                    <div class="p-3">
                        <h6 class="mb-1">${design.title}</h6>
                        <small class="text-muted">${design.description || 'No description'}</small>
                        ${design.price_adjustment > 0 ?
                            `<div class="mt-2"><small class="text-success">+₹${design.price_adjustment}</small></div>` :
                            ''
                        }
                    </div>
                </div>
            </div>
        `;
        });

        container.innerHTML = html;
    }

    function selectDesign(designId) {
        document.querySelectorAll('.design-card').forEach(card => {
            card.classList.remove('selected');
        });

        event.currentTarget.classList.add('selected');
        document.querySelector(`input[name="design_catalog_id"][value="${designId}"]`).checked = true;

        // Show the continue button
        document.getElementById('designNextBtn').style.display = 'inline-block';
    }

    function proceedWithDesign() {
        changeStep(1);
    }

    function skipDesign() {
        document.querySelectorAll('.design-card').forEach(card => {
            card.classList.remove('selected');
        });
        document.querySelectorAll('input[name="design_catalog_id"]').forEach(input => {
            input.checked = false;
        });
        changeStep(1);
    }

    // Measurements functions for multiple services
    function loadCustomerMeasurements() {
        if (!selectedCustomerId || selectedServices.length === 0) {
            console.log('Missing customer or services:', { customerId: selectedCustomerId, services: selectedServices });
            return;
        }

        console.log('Loading measurements for customer:', selectedCustomerId, 'services:', selectedServices);

        const container = document.getElementById('measurementsContainer');
        const multipleContainer = document.getElementById('multipleServicesMeasurements');
        const accordion = document.getElementById('measurementsAccordion');

        container.style.display = 'none';
        multipleContainer.style.display = 'block';

        let accordionHtml = '';

        selectedServices.forEach((service, index) => {
            accordionHtml += `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading${service.id}">
                        <button class="accordion-button ${index === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${service.id}">
                            <i class="ri-scissors-line me-2"></i>
                            ${service.name} Measurements
                        </button>
                    </h2>
                    <div id="collapse${service.id}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" data-bs-parent="#measurementsAccordion">
                        <div class="accordion-body">
                            <div id="measurements_${service.id}">
                                <div class="text-center">
                                    <i class="ri-loader-4-line spin"></i> Loading measurements...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        accordion.innerHTML = accordionHtml;

        // Load measurements for each service
        selectedServices.forEach(service => {
            loadServiceMeasurements(service.id);
        });
    }

    function loadServiceMeasurements(serviceId) {
        fetch(`/api/customers/${selectedCustomerId}/services/${serviceId}/measurements`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById(`measurements_${serviceId}`);

                if (data.success && data.measurements.length > 0) {
                    displayServiceMeasurements(serviceId, data.measurements);
                } else {
                    showNoServiceMeasurements(serviceId);
                }
            })
            .catch(error => {
                console.error('Error loading measurements for service', serviceId, ':', error);
                showNoServiceMeasurements(serviceId);
            });
    }

    function displayServiceMeasurements(serviceId, measurements) {
        const container = document.getElementById(`measurements_${serviceId}`);

        let html = '<div class="d-flex justify-content-between align-items-center mb-3">';
        html += '<h6 class="text-success mb-0"><i class="ri-check-line me-1"></i>Existing Measurements</h6>';
        html += `<button type="button" class="btn btn-sm btn-outline-primary" onclick="editServiceMeasurements(${serviceId})">`;
        html += '<i class="ri-edit-line me-1"></i>Edit</button>';
        html += '</div>';

        html += '<div class="row" id="measurementsDisplay_' + serviceId + '">';

        measurements.forEach(measurement => {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card border-success">
                        <div class="card-body p-3">
                            <h6 class="mb-1">${measurement.measurement_attribute.name}</h6>
                            <p class="mb-0 text-success"><strong>${measurement.value} inch</strong></p>
                            ${measurement.notes ? `<small class="text-muted">${measurement.notes}</small>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';

        // Hidden edit form
        html += `<div id="editMeasurementForm_${serviceId}" style="display: none;"></div>`;

        container.innerHTML = html;
    }

    function editServiceMeasurements(serviceId) {
        const service = selectedServices.find(s => s.id === serviceId);
        const displayDiv = document.getElementById(`measurementsDisplay_${serviceId}`);
        const editFormDiv = document.getElementById(`editMeasurementForm_${serviceId}`);

        // Hide display and show edit form
        displayDiv.style.display = 'none';

        // Load current measurements for editing
        fetch(`/api/customers/${selectedCustomerId}/services/${serviceId}/measurements`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.measurements.length > 0) {
                    displayEditMeasurementForm(serviceId, data.measurements);
                }
            })
            .catch(error => {
                console.error('Error loading measurements for edit:', error);
            });
    }

    function displayEditMeasurementForm(serviceId, measurements) {
        const container = document.getElementById(`editMeasurementForm_${serviceId}`);
        const service = selectedServices.find(s => s.id === serviceId);

        let html = `<h6 class="mb-3">Edit Measurements for ${service.name}</h6>`;
        html += '<div class="row">';

        measurements.forEach(measurement => {
            html += `
                <div class="col-md-6 mb-3">
                    <label class="form-label">${measurement.measurement_attribute.name}</label>
                    <div class="input-group">
                        <input type="text" class="form-control edit-measurement-input"
                               data-service-id="${serviceId}"
                               data-attribute-id="${measurement.measurement_attribute.id}"
                               data-measurement-id="${measurement.id}"
                               value="${measurement.value}"
                               placeholder="Enter ${measurement.measurement_attribute.name}" required>
                        <span class="input-group-text">inch</span>
                    </div>
                    <textarea class="form-control mt-2 edit-measurement-notes"
                              data-service-id="${serviceId}"
                              data-attribute-id="${measurement.measurement_attribute.id}"
                              placeholder="Notes for ${measurement.measurement_attribute.name}"
                              rows="2">${measurement.notes || ''}</textarea>
                </div>
            `;
        });

        html += '</div>';
        html += `<div class="mt-3">`;
        html += `<button type="button" class="btn btn-success me-2" onclick="updateServiceMeasurements(${serviceId})">`;
        html += '<i class="ri-save-line me-1"></i>Update Measurements</button>';
        html += `<button type="button" class="btn btn-secondary" onclick="cancelEditMeasurements(${serviceId})">`;
        html += '<i class="ri-close-line me-1"></i>Cancel</button>';
        html += `</div>`;

        container.innerHTML = html;
        container.style.display = 'block';
    }

    function updateServiceMeasurements(serviceId) {
        const measurements = [];
        const inputs = document.querySelectorAll(`.edit-measurement-input[data-service-id="${serviceId}"]`);

        inputs.forEach(input => {
            const attributeId = input.dataset.attributeId;
            const measurementId = input.dataset.measurementId;
            const value = input.value.trim();
            const notesElement = document.querySelector(`.edit-measurement-notes[data-service-id="${serviceId}"][data-attribute-id="${attributeId}"]`);
            const notes = notesElement ? notesElement.value.trim() : '';

            if (value) {
                measurements.push({
                    id: measurementId,
                    attribute_id: attributeId,
                    value: value,
                    notes: notes
                });
            }
        });

        if (measurements.length === 0) {
            alert('Please enter at least one measurement');
            return;
        }

        // Update measurements via API
        fetch('/api/measurements/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                customer_id: selectedCustomerId,
                service_id: serviceId,
                measurements: measurements
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Measurements updated successfully!');
                loadServiceMeasurements(serviceId);
            } else {
                alert('Error updating measurements: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating measurements: ' + error.message);
        });
    }

    function cancelEditMeasurements(serviceId) {
        const displayDiv = document.getElementById(`measurementsDisplay_${serviceId}`);
        const editFormDiv = document.getElementById(`editMeasurementForm_${serviceId}`);

        displayDiv.style.display = 'block';
        editFormDiv.style.display = 'none';
    }

    function showNoServiceMeasurements(serviceId) {
        const container = document.getElementById(`measurements_${serviceId}`);
        const service = selectedServices.find(s => s.id === serviceId);

        container.innerHTML = `
            <div class="alert alert-warning">
                <h6 class="mb-2"><i class="ri-alert-line me-1"></i>No Measurements Found</h6>
                <p class="mb-2">This customer doesn't have measurements for ${service.name}.</p>
                <button type="button" class="btn btn-sm btn-primary" onclick="showServiceMeasurementForm(${serviceId})">
                    <i class="ri-add-line me-1"></i>Add Measurements
                </button>
            </div>
            <div id="measurementForm_${serviceId}" style="display: none;"></div>
        `;
    }

    function showServiceMeasurementForm(serviceId) {
        fetch(`/api/services/${serviceId}/measurements`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.measurements.length > 0) {
                    displayServiceMeasurementForm(serviceId, data.measurements);
                } else {
                    alert('No measurement attributes defined for this service');
                }
            })
            .catch(error => {
                console.error('Error loading attributes:', error);
                alert('Error loading measurement attributes');
            });
    }

    function displayServiceMeasurementForm(serviceId, attributes) {
        const container = document.getElementById(`measurementForm_${serviceId}`);
        const service = selectedServices.find(s => s.id === serviceId);

        let html = `<h6 class="mb-3">Add Measurements for ${service.name}</h6>`;
        html += '<div class="row">';

        attributes.forEach(attr => {
            html += `
                <div class="col-md-6 mb-3">
                    <label class="form-label">${attr.name}</label>
                    <div class="input-group">
                        <input type="text" class="form-control measurement-input"
                               data-service-id="${serviceId}" data-attribute-id="${attr.id}"
                               placeholder="Enter ${attr.name}" required>
                        <span class="input-group-text">inch</span>
                    </div>
                    <textarea class="form-control mt-2 measurement-notes"
                              data-service-id="${serviceId}" data-attribute-id="${attr.id}"
                              placeholder="Notes for ${attr.name}" rows="2"></textarea>
                </div>
            `;
        });

        html += '</div>';
        html += `<button type="button" class="btn btn-success mt-3" onclick="saveServiceMeasurements(${serviceId})">
                    <i class="ri-save-line me-1"></i>Save Measurements
                 </button>`;

        container.innerHTML = html;
        container.style.display = 'block';
    }

    function saveServiceMeasurements(serviceId) {
        const measurements = [];
        const inputs = document.querySelectorAll(`.measurement-input[data-service-id="${serviceId}"]`);

        inputs.forEach(input => {
            const attributeId = input.dataset.attributeId;
            const value = input.value.trim();
            const notesElement = document.querySelector(`.measurement-notes[data-service-id="${serviceId}"][data-attribute-id="${attributeId}"]`);
            const notes = notesElement ? notesElement.value.trim() : '';

            if (value) {
                measurements.push({
                    attribute_id: attributeId,
                    value: value,
                    notes: notes
                });
            }
        });

        if (measurements.length === 0) {
            alert('Please enter at least one measurement');
            return;
        }

        fetch('/api/measurements/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                customer_id: selectedCustomerId,
                service_id: serviceId,
                measurements: measurements
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Measurements saved successfully!');
                loadServiceMeasurements(serviceId);
            } else {
                alert('Error saving measurements: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving measurements: ' + error.message);
        });
    }

    // Design functions for multiple services
    function loadMultipleServicesDesigns() {
        if (selectedServices.length === 0) {
            return;
        }

        const multipleContainer = document.getElementById('multipleServicesDesigns');
        const accordion = document.getElementById('designsAccordion');

        multipleContainer.style.display = 'block';

        let accordionHtml = '';

        selectedServices.forEach((service, index) => {
            accordionHtml += `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="designHeading${service.id}">
                        <button class="accordion-button ${index === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#designCollapse${service.id}">
                            <i class="ri-palette-line me-2"></i>
                            ${service.name} Designs
                        </button>
                    </h2>
                    <div id="designCollapse${service.id}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" data-bs-parent="#designsAccordion">
                        <div class="accordion-body">
                            <div id="designs_${service.id}">
                                <div class="text-center">
                                    <i class="ri-loader-4-line spin"></i> Loading designs...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        accordion.innerHTML = accordionHtml;

        // Load designs for each service
        selectedServices.forEach(service => {
            loadServiceDesigns(service.id);
        });
    }

    function loadServiceDesigns(serviceId) {
        const designs = {!! json_encode($designs) !!};
        const serviceDesigns = designs.filter(design => design.service_id == serviceId);
        const container = document.getElementById(`designs_${serviceId}`);

        if (serviceDesigns.length === 0) {
            container.innerHTML = '<div class="text-center text-muted"><p>No designs available for this service</p></div>';
            return;
        }

        let html = '<div class="row">';
        serviceDesigns.forEach(design => {
            html += `
                <div class="col-md-4 mb-3">
                    <div class="design-card" onclick="selectServiceDesign(${serviceId}, ${design.id})">
                        <input type="checkbox" name="service_designs[${serviceId}]" value="${design.id}" class="d-none service-design-checkbox" id="design_${serviceId}_${design.id}">
                        ${design.image_path ?
                            `<img src="/${design.image_path}" class="card-img-top" style="height: 150px; object-fit: cover;">` :
                            `<div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="ri-image-line text-muted" style="font-size: 2rem;"></i>
                            </div>`
                        }
                        <div class="p-3">
                            <h6 class="mb-1">${design.title}</h6>
                            <small class="text-muted">${design.description || 'No description'}</small>
                            ${design.price_adjustment > 0 ?
                                `<div class="mt-2"><small class="text-success">+Rs${design.price_adjustment}</small></div>` :
                                ''
                            }
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';

        html += `<div class="mt-3 text-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="skipServiceDesign(${serviceId})">
                        Skip Design for this Service
                    </button>
                 </div>`;

        container.innerHTML = html;
    }

    function selectServiceDesign(serviceId, designId) {
        const checkbox = document.getElementById(`design_${serviceId}_${designId}`);
        const designCard = checkbox.closest('.design-card');

        // Remove selection from other designs for this service
        document.querySelectorAll(`input[name="service_designs[${serviceId}]"]`).forEach(input => {
            input.checked = false;
            input.closest('.design-card').classList.remove('selected');
        });

        // Select current design
        checkbox.checked = true;
        designCard.classList.add('selected');
    }

    function skipServiceDesign(serviceId) {
        document.querySelectorAll(`input[name="service_designs[${serviceId}]"]`).forEach(input => {
            input.checked = false;
            input.closest('.design-card').classList.remove('selected');
        });
    }

    function skipAllDesigns() {
        document.querySelectorAll('.service-design-checkbox').forEach(input => {
            input.checked = false;
            input.closest('.design-card').classList.remove('selected');
        });
        changeStep(1);
    }

    function displayExistingMeasurements(measurements) {
        document.getElementById('measurementsContainer').style.display = 'none';
        document.getElementById('noMeasurements').style.display = 'none';
        document.getElementById('newMeasurementForm').style.display = 'none';

        const existingDiv = document.getElementById('existingMeasurements');
        const listDiv = document.getElementById('measurementsList');

        let html = '<div class="row">';
        measurements.forEach(measurement => {
            html += `
            <div class="col-md-6 mb-3">
                <div class="card border-success">
                    <div class="card-body p-3">
                        <h6 class="mb-1">${measurement.measurement_attribute.name}</h6>
                        <p class="mb-0 text-success"><strong>${measurement.value} inch</strong></p>
                        ${measurement.notes ? `<small class="text-muted">${measurement.notes}</small>` : ''}
                    </div>
                </div>
            </div>
        `;
        });
        html += '</div>';

        listDiv.innerHTML = html;
        existingDiv.style.display = 'block';
    }

    function showNoMeasurements() {
        document.getElementById('measurementsContainer').style.display = 'none';
        document.getElementById('existingMeasurements').style.display = 'none';
        document.getElementById('newMeasurementForm').style.display = 'none';
        document.getElementById('noMeasurements').style.display = 'block';
    }

    function showMeasurementForm() {
        document.getElementById('noMeasurements').style.display = 'none';

        console.log('Loading measurement attributes for service:', selectedServiceId);

        // Load measurement attributes for this service
        fetch(`/api/services/${selectedServiceId}/measurements`)
            .then(response => {
                console.log('Attributes response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Attributes data:', data);
                if (data.success && data.measurements.length > 0) {
                    displayMeasurementForm(data.measurements);
                } else {
                    alert('No measurement attributes defined for this service');
                }
            })
            .catch(error => {
                console.error('Error loading attributes:', error);
                alert('Error loading measurement attributes');
            });
    }

    function displayMeasurementForm(attributes) {
        const fieldsDiv = document.getElementById('measurementFields');

        let html = '<div class="row">';
        attributes.forEach(attr => {
            html += `
            <div class="col-md-6 mb-3">
                <label class="form-label">${attr.name}</label>
                <div class="input-group">
                    <input type="text"
                        class="form-control measurement-input"
                        data-attribute-id="${attr.id}"
                        placeholder="Enter ${attr.name}"
                        required>
                    <span class="input-group-text">inch</span>
                </div>
                <textarea class="form-control mt-2 measurement-notes"
                          data-attribute-id="${attr.id}"
                          placeholder="Notes for ${attr.name}"
                          rows="2"></textarea>
            </div>
        `;
        });
        html += '</div>';

        fieldsDiv.innerHTML = html;
        document.getElementById('newMeasurementForm').style.display = 'block';
    }

    function saveMeasurements() {
        const measurements = [];
        const measurementInputs = document.querySelectorAll('.measurement-input');

        measurementInputs.forEach(input => {
            const attributeId = input.dataset.attributeId;
            const value = input.value.trim();
            const notesElement = document.querySelector(`.measurement-notes[data-attribute-id="${attributeId}"]`);
            const notes = notesElement ? notesElement.value.trim() : '';

            if (value) {
                measurements.push({
                    attribute_id: attributeId,
                    value: value,
                    notes: notes
                });
            }
        });

        if (measurements.length === 0) {
            alert('Please enter at least one measurement');
            return;
        }

        console.log('Saving measurements:', {
            customer_id: selectedCustomerId,
            service_id: selectedServiceId,
            measurements: measurements
        });

        // Save measurements
        fetch('/api/measurements/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    customer_id: selectedCustomerId,
                    service_id: selectedServiceId,
                    measurements: measurements
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert('Measurements saved successfully!');
                    // Reload measurements to show the saved ones
                    loadCustomerMeasurements();
                } else {
                    alert('Error saving measurements: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving measurements: ' + error.message);
            });
    }

    function skipMeasurements() {
        changeStep(1);
    }

    // Payment calculations
    function updatePaymentSummary() {
        const total = parseFloat(document.getElementById('totalAmount').value) || 0;
        const advance = parseFloat(document.getElementById('advanceAmount').value) || 0;
        const remaining = total - advance;

        document.getElementById('summaryTotal').textContent = `Rs${total.toLocaleString()}`;
        document.getElementById('summaryAdvance').textContent = `Rs${advance.toLocaleString()}`;
        document.getElementById('summaryRemaining').textContent = `Rs${remaining.toLocaleString()}`;
    }

    document.getElementById('totalAmount').addEventListener('input', updatePaymentSummary);
    document.getElementById('advanceAmount').addEventListener('input', updatePaymentSummary);

    // New customer form
    document.getElementById('newCustomerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("Dashboard.customers.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    // Add new customer to select
                    const select = document.getElementById('customerSelect');
                    const option = new Option(`${data.customer.name} - ${data.customer.phone}`, data.customer.id);
                    option.dataset.phone = data.customer.phone;
                    option.dataset.address = data.customer.address || '';
                    select.add(option);
                    select.value = data.customer.id;

                    // Trigger change event
                    select.dispatchEvent(new Event('change'));

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('newCustomerModal')).hide();
                    this.reset();
                    
                    // Show success message
                    alert('Customer added successfully!');
                } else {
                    alert('Error: ' + (data.message || 'Unknown error occurred'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error adding customer: ' + error.message);
            });
    });

    // Initialize
    updateButtons();
    updatePaymentSummary();

    // Handle form submission with AJAX
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (currentStep !== 6) return;

        const formData = new FormData(this);
        const submitBtn = document.getElementById('submitBtn');

        // Disable submit button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i>Creating Booking...';

        fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    createdBookingId = data.booking_id;
                    
                    // Force clear saved state immediately
                    console.log('Booking created successfully, clearing storage...');
                    clearSavedState();
                    
                    // Verify storage is cleared
                    console.log('Storage after clear:', {
                        step: localStorage.getItem('booking_step'),
                        customer: localStorage.getItem('booking_customer'),
                        services: localStorage.getItem('booking_services')
                    });
                    
                    showSuccessStep(data.booking);
                    changeStep(1); // Move to step 7
                } else {
                    alert('Error creating booking: ' + (data.message || 'Unknown error'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ri-check-line me-1"></i>Create Booking';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error creating booking: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ri-check-line me-1"></i>Create Booking';
            });
    });

    // Show success step with booking details
    function showSuccessStep(booking) {
        const summary = document.getElementById('bookingSummary');
        const downloadBtn = document.getElementById('downloadInvoiceBtn');

        // Populate booking summary
        document.getElementById('summaryBookingId').textContent = '#' + String(booking.id).padStart(6, '0');
        document.getElementById('summaryCustomerName').textContent = booking.customer.name;

        // Display multiple services from bookingItems
        let servicesText = '';
        if (booking.booking_items && booking.booking_items.length > 0) {
            servicesText = booking.booking_items.map(item => item.service.name).join(', ');
        } else {
            // Fallback to selected services from form
            servicesText = selectedServices.map(service => service.name).join(', ');
        }
        document.getElementById('summaryServiceName').textContent = servicesText;

        document.getElementById('summaryBookingDate').textContent = new Date(booking.booking_date).toLocaleDateString();
        document.getElementById('summaryDeliveryDate').textContent = new Date(booking.delivery_date).toLocaleDateString();
        document.getElementById('summaryTotalAmount').textContent = 'Rs' + Number(booking.total_amount).toLocaleString();

        // Show summary and enable download button
        summary.style.display = 'block';
        downloadBtn.disabled = false;
    }

    // Download invoice function
    function downloadInvoice() {
        if (createdBookingId) {
            const invoiceUrl = '{{ route("Dashboard.bookings.invoice", ":id") }}'.replace(':id', createdBookingId);
            window.open(invoiceUrl, '_blank');
        }
    }

    // Create new booking function
    function createNewBooking() {
        // Don't clear saved state - let it persist for the new booking form
        console.log('Creating new booking without clearing storage...');
        window.location.reload();
    }

    // Manual storage clear function
    function manualClearStorage() {
        if (confirm('Are you sure you want to clear the storage? This will reset all form data.')) {
            clearSavedState();
            alert('Storage cleared successfully!');
            window.location.reload();
        }
    }
</script>
@endsection
