<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubServiceController;
use App\Http\Controllers\DesignCatalogController;
use App\Http\Controllers\ServiceVariantController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MeasurementAttributeController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;



Route::get('/', function () {
    return view('welcome');
});



    Route::get('/', function () {
        return view('Dashboard.dashboard');
    })->name('dashboard');
Route::prefix('Dashboard')->name('Dashboard.')->group(function () {
    // Base Services
    Route::get('services/base', [ServiceController::class, 'baseServices'])->name('services.base');

    // Add-on Services
    Route::get('services/addon', [ServiceController::class, 'addonServices'])->name('services.addon');

    // Standard CRUD routes for services
    Route::get('services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // Resource Routes
    Route::resource('design-catalog', DesignCatalogController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('measurement-attributes', MeasurementAttributeController::class);
    Route::resource('measurements', MeasurementController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('payments', PaymentController::class);
    
    // Booking specific routes
    Route::get('bookings-today', [BookingController::class, 'today'])->name('bookings.today');
    Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

    // Payment specific routes
    Route::get('payments-today', [PaymentController::class, 'todayPayments'])->name('payments.today');
    Route::get('payments-pending', [PaymentController::class, 'pending'])->name('payments.pending');
    Route::get('bookings/{booking}/payments', [PaymentController::class, 'getBookingPayments'])->name('bookings.payments');
    Route::get('bookings/{booking}/invoice', [BookingController::class, 'downloadInvoice'])->name('bookings.invoice');
});

// API Routes
Route::get('api/bookings/{booking}', [BookingController::class, 'getBooking'])->name('api.bookings.show');

// AJAX route
Route::get(
    'services/{service}/measurement-attributes',
    [MeasurementController::class, 'getServiceAttributes']
)->name('services.measurement.attributes');

// New measurement routes
Route::get('api/services/{service}/measurements', [MeasurementController::class, 'getServiceMeasurements'])->name('api.services.measurements');
Route::post('api/measurements/store', [MeasurementController::class, 'storeMeasurements'])->name('api.measurements.store');
Route::post('api/measurements/update', [MeasurementController::class, 'updateMeasurements'])->name('api.measurements.update');
Route::get('api/customers/{customer}/services/{service}/measurements', [MeasurementController::class, 'getCustomerMeasurements'])->name('api.customers.measurements');

