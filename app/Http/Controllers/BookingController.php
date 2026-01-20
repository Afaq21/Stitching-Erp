<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\DesignCatalog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Booking::with(['customer', 'bookingItems.service', 'bookingItems.designCatalog'])
            ->latest();

        // Apply search filter
        if ($search) {
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(12);
        
        // Preserve search parameter in pagination
        $bookings->appends($request->only('search'));

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', Booking::STATUS_PENDING)->count(),
            'confirmed' => Booking::where('status', Booking::STATUS_CONFIRMED)->count(),
            'in_progress' => Booking::where('status', Booking::STATUS_IN_PROGRESS)->count(),
            'ready' => Booking::where('status', Booking::STATUS_READY)->count(),
        ];

        // Data for edit modal
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $designs = DesignCatalog::with('service')->orderBy('title')->get();

        return view('Dashboard.bookings.index', compact('bookings', 'stats', 'customers', 'services', 'designs'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $designs = DesignCatalog::with('service')->orderBy('title')->get();
        $statuses = Booking::getStatuses();

        return view('Dashboard.bookings.create', compact('customers', 'services', 'designs', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'services' => 'required|array|min:1',
            'services.*' => 'required|exists:services,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1|max:10',
            'booking_date' => 'required|date',
            'delivery_date' => 'required|date|after_or_equal:booking_date',
            'total_amount' => 'required|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(Booking::getStatuses())),
            'notes' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high'
        ]);

        // Create the main booking record
        $bookingData = $request->except(['services', 'quantities', 'service_designs']);
        $bookingData['remaining_amount'] = $bookingData['total_amount'] - ($bookingData['advance_amount'] ?? 0);

        $booking = Booking::create($bookingData);

        // Create BookingItem records for each selected service with quantities
        $services = \App\Models\Service::whereIn('id', $request->services)->get();
        
        foreach ($services as $service) {
            $quantity = $request->quantities[$service->id] ?? 1;
            $unitPrice = $service->price;
            $totalPrice = $unitPrice * $quantity;
            
            // Get design for this service if selected
            $designId = null;
            if ($request->has('service_designs') && isset($request->service_designs[$service->id])) {
                $designId = $request->service_designs[$service->id];
            }
            
            \App\Models\BookingItem::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'design_catalog_id' => $designId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'notes' => null
            ]);
        }

        // Return JSON response for AJAX handling
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully!',
                'booking_id' => $booking->id,
                'booking' => $booking->load(['customer', 'bookingItems.service', 'bookingItems.designCatalog'])
            ]);
        }

        return redirect()->route('Dashboard.bookings.index')
            ->with('success', 'Booking created successfully!');
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'bookingItems.service', 'bookingItems.designCatalog', 'payments']);
        
        return view('Dashboard.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        // Redirect to index page with edit modal
        return redirect()->route('Dashboard.bookings.index')->with('edit_booking', $booking->id);
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'booking_date' => 'required|date',
            'delivery_date' => 'required|date|after_or_equal:booking_date',
            'total_amount' => 'required|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(Booking::getStatuses())),
            'notes' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high'
        ]);

        $data = $request->all();
        $data['remaining_amount'] = $data['total_amount'] - ($data['advance_amount'] ?? 0);

        $booking->update($data);

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully!',
                'booking' => $booking->load(['customer', 'bookingItems.service', 'bookingItems.designCatalog'])
            ]);
        }

        return redirect()->route('Dashboard.bookings.index')
            ->with('success', 'Booking updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        $booking->bookingItems()->delete();
        $booking->delete();

        return redirect()->route('Dashboard.bookings.index')
            ->with('success', 'Booking deleted successfully!');
    }

    // Additional methods
    public function today(Request $request)
    {
        $search = $request->input('search');

        $query = Booking::with(['customer', 'bookingItems.service', 'bookingItems.designCatalog'])
            ->today()
            ->latest();

        // Apply search filter
        if ($search) {
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(12);
        
        // Preserve search parameter in pagination
        $bookings->appends($request->only('search'));

        // Data for edit modal
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $designs = DesignCatalog::with('service')->orderBy('title')->get();

        return view('Dashboard.bookings.today', compact('bookings', 'customers', 'services', 'designs'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Booking::getStatuses()))
        ]);

        $booking->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status' => $booking->status_text,
            'badge' => $booking->status_badge
        ]);
    }

    // API endpoint to get booking data
    public function getBooking(Booking $booking)
    {
        $booking->load(['customer', 'bookingItems.service', 'bookingItems.designCatalog']);
        
        return response()->json([
            'id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'booking_date' => $booking->booking_date->format('Y-m-d'),
            'delivery_date' => $booking->delivery_date->format('Y-m-d'),
            'status' => $booking->status,
            'total_amount' => $booking->total_amount,
            'advance_amount' => $booking->advance_amount,
            'remaining_amount' => $booking->remaining_amount,
            'priority' => $booking->priority,
            'notes' => $booking->notes,
            'customer' => $booking->customer,
            'bookingItems' => $booking->bookingItems
        ]);
    }

    // AJAX methods for edit functionality
    public function getBookingData(Booking $booking)
    {
        $booking->load(['customer', 'bookingItems.service', 'bookingItems.designCatalog']);
        
        return response()->json([
            'success' => true,
            'booking' => $booking,
            'customer' => $booking->customer,
            'bookingItems' => $booking->bookingItems
        ]);
    }

    public function updateAjax(Request $request, Booking $booking)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'design_catalog_id' => 'nullable|exists:design_catalogs,id',
            'booking_date' => 'required|date',
            'delivery_date' => 'required|date|after_or_equal:booking_date',
            'total_amount' => 'required|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(Booking::getStatuses())),
            'notes' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high'
        ]);

        $data = $request->all();
        $data['remaining_amount'] = $data['total_amount'] - ($data['advance_amount'] ?? 0);

        $booking->update($data);
        $booking->load(['customer', 'bookingItems.service', 'bookingItems.designCatalog']);

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully!',
            'booking' => $booking
        ]);
    }

    // Invoice generation
    public function generateInvoice(Booking $booking)
    {
        $booking->load(['customer', 'bookingItems.service', 'bookingItems.designCatalog']);
        
        $pdf = Pdf::loadView('Dashboard.bookings.invoice', compact('booking'));
        
        $filename = 'invoice-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        
        return $pdf->download($filename);
    }

    // Route for manual invoice generation
    public function downloadInvoice(Booking $booking)
    {
        return $this->generateInvoice($booking);
    }
}