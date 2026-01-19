@extends('layouts.master')

@section('css')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 3rem 2rem;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    background: linear-gradient(45deg, #ffffff, #f8f9ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.3rem;
    font-weight: 300;
    opacity: 0.9;
    margin-bottom: 2rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

.hero-features {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.hero-feature {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.1);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.hero-feature i {
    font-size: 1.2rem;
}

.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.floating-element {
    position: absolute;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    animation: floatUp 15s ease-in-out infinite;
}

.floating-element:nth-child(1) {
    width: 60px;
    height: 60px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.floating-element:nth-child(2) {
    width: 40px;
    height: 40px;
    top: 60%;
    left: 80%;
    animation-delay: 5s;
}

.floating-element:nth-child(3) {
    width: 80px;
    height: 80px;
    top: 80%;
    left: 20%;
    animation-delay: 10s;
}

@keyframes floatUp {
    0%, 100% { 
        transform: translateY(0px) scale(1);
        opacity: 0.3;
    }
    50% { 
        transform: translateY(-30px) scale(1.1);
        opacity: 0.6;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .hero-features {
        gap: 1rem;
    }
    
    .hero-section {
        padding: 2rem 1rem;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>
        
        <div class="hero-content text-center">
            <h1 class="hero-title">Stitching ERP</h1>
            <p class="hero-subtitle">
                Complete Tailoring Management System - From Measurements to Delivery
            </p>
            
            <div class="hero-features justify-content-center">
                <div class="hero-feature">
                    <i class="ri-scissors-line"></i>
                    <span>Smart Tailoring</span>
                </div>
                <div class="hero-feature">
                    <i class="ri-user-line"></i>
                    <span>Customer Management</span>
                </div>
                <div class="hero-feature">
                    <i class="ri-calendar-check-line"></i>
                    <span>Order Tracking</span>
                </div>
                <div class="hero-feature">
                    <i class="ri-money-dollar-circle-line"></i>
                    <span>Payment Management</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 col-12 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary rounded-circle me-3">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div>
                            <h3 class="mb-1">{{ \App\Models\Booking::count() }}</h3>
                            <p class="text-muted mb-0">Total Bookings</p>
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
                            <i class="ri-user-line"></i>
                        </div>
                        <div>
                            <h3 class="mb-1">{{ \App\Models\Customer::count() }}</h3>
                            <p class="text-muted mb-0">Total Customers</p>
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
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                        <div>
                            <h3 class="mb-1">Rs{{ number_format(\App\Models\Payment::completed()->sum('amount'), 0) }}</h3>
                            <p class="text-muted mb-0">Total Revenue</p>
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
                            <h3 class="mb-1">{{ \App\Models\Booking::where('status', 'pending')->count() }}</h3>
                            <p class="text-muted mb-0">Pending Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-rocket-line me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <a href="{{ route('Dashboard.bookings.create') }}" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 text-decoration-none">
                                <i class="ri-add-circle-line fs-1 mb-2"></i>
                                <span class="fw-medium">New Booking</span>
                                <small class="opacity-75">Create new order</small>
                            </a>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <a href="{{ route('Dashboard.customers.index') }}" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 text-decoration-none">
                                <i class="ri-user-add-line fs-1 mb-2"></i>
                                <span class="fw-medium">Add Customer</span>
                                <small class="opacity-75">Register new customer</small>
                            </a>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <a href="{{ route('Dashboard.payments.create') }}" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 text-decoration-none">
                                <i class="ri-money-dollar-circle-line fs-1 mb-2"></i>
                                <span class="fw-medium">Add Payment</span>
                                <small class="opacity-75">Record payment</small>
                            </a>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <a href="{{ route('Dashboard.measurements.index') }}" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 text-decoration-none">
                                <i class="ri-ruler-line fs-1 mb-2"></i>
                                <span class="fw-medium">Take Measurements</span>
                                <small class="opacity-75">Record measurements</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
</style>
@endsection
@section('script')

@endsection