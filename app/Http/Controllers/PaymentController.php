<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Payment::with(['booking.customer', 'customer'])
            ->latest();

        // Apply search filter
        if ($search) {
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(15);
        
        // Preserve search parameter in pagination
        $payments->appends($request->only('search'));

        $stats = [
            'total_payments' => Payment::completed()->count(),
            'today_payments' => Payment::completed()->today()->count(),
            'total_amount' => Payment::completed()->sum('amount'),
            'today_amount' => Payment::completed()->today()->sum('amount'),
        ];

        return view('Dashboard.payments.index', compact('payments', 'stats'));
    }

    public function create(Request $request)
    {
        $booking = null;
        if ($request->has('booking_id')) {
            $booking = Booking::with(['customer', 'bookingItems.service'])
                ->findOrFail($request->booking_id);
        }

        $customers = Customer::orderBy('name')->get();
        $bookings = Booking::with('customer')
            ->where('total_amount', '>', 0)
            ->latest()
            ->get();

        $paymentTypes = Payment::getPaymentTypes();
        $paymentMethods = Payment::getPaymentMethods();

        return view('Dashboard.payments.create', compact(
            'booking', 'customers', 'bookings', 'paymentTypes', 'paymentMethods'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:' . implode(',', array_keys(Payment::getPaymentTypes())),
            'payment_method' => 'required|in:' . implode(',', array_keys(Payment::getPaymentMethods())),
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        
        // Check if payment amount doesn't exceed remaining amount
        $remainingAmount = $booking->remaining_amount;
        if ($request->amount > $remainingAmount) {
            return back()->withErrors([
                'amount' => "Payment amount cannot exceed remaining amount of Rs{$remainingAmount}"
            ])->withInput();
        }

        // Create payment record
        $payment = Payment::create([
            'booking_id' => $request->booking_id,
            'customer_id' => $booking->customer_id,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'status' => Payment::STATUS_COMPLETED
        ]);

        // Update booking amounts
        $newAdvanceAmount = $booking->advance_amount + $request->amount;
        $newRemainingAmount = $booking->total_amount - $newAdvanceAmount;
        
        $booking->update([
            'advance_amount' => $newAdvanceAmount,
            'remaining_amount' => $newRemainingAmount
        ]);

        // Auto-update booking status if fully paid
        if ($newRemainingAmount <= 0) {
            if ($booking->status === Booking::STATUS_READY) {
                // Can be delivered now
                $booking->update(['status' => Booking::STATUS_READY]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment added successfully!',
                'payment' => $payment->load(['booking.customer']),
                'booking' => $booking->fresh()
            ]);
        }

        return redirect()->route('Dashboard.payments.index')
            ->with('success', 'Payment added successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.customer', 'booking.bookingItems.service', 'customer']);
        
        return view('Dashboard.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $payment->load(['booking.customer']);
        $paymentTypes = Payment::getPaymentTypes();
        $paymentMethods = Payment::getPaymentMethods();
        $statuses = Payment::getStatuses();

        return view('Dashboard.payments.edit', compact('payment', 'paymentTypes', 'paymentMethods', 'statuses'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:' . implode(',', array_keys(Payment::getPaymentTypes())),
            'payment_method' => 'required|in:' . implode(',', array_keys(Payment::getPaymentMethods())),
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_keys(Payment::getStatuses()))
        ]);

        $booking = $payment->booking;
        $oldAmount = $payment->amount;
        $newAmount = $request->amount;
        
        // Calculate the difference
        $amountDifference = $newAmount - $oldAmount;
        
        // Update payment record
        $payment->update($request->all());
        
        // Update booking amounts if payment amount changed
        if ($amountDifference != 0) {
            $newAdvanceAmount = $booking->advance_amount + $amountDifference;
            $newRemainingAmount = $booking->total_amount - $newAdvanceAmount;
            
            $booking->update([
                'advance_amount' => $newAdvanceAmount,
                'remaining_amount' => $newRemainingAmount
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully!',
                'payment' => $payment->load(['booking.customer']),
                'booking' => $booking->fresh()
            ]);
        }

        return redirect()->route('Dashboard.payments.index')
            ->with('success', 'Payment updated successfully!');
    }

    public function destroy(Payment $payment)
    {
        $booking = $payment->booking;
        $paymentAmount = $payment->amount;
        
        // Delete payment
        $payment->delete();
        
        // Update booking amounts - subtract the deleted payment amount
        $newAdvanceAmount = $booking->advance_amount - $paymentAmount;
        $newRemainingAmount = $booking->total_amount - $newAdvanceAmount;
        
        $booking->update([
            'advance_amount' => $newAdvanceAmount,
            'remaining_amount' => $newRemainingAmount
        ]);

        return redirect()->route('Dashboard.payments.index')
            ->with('success', 'Payment deleted successfully!');
    }

    // Additional methods
    public function getBookingPayments(Booking $booking)
    {
        $payments = $booking->payments()->with('customer')->latest()->get();
        
        return response()->json([
            'success' => true,
            'payments' => $payments,
            'booking_summary' => [
                'total_amount' => $booking->total_amount,
                'total_paid' => $booking->total_paid,
                'remaining_amount' => $booking->remaining_amount,
                'payment_status' => $booking->payment_status_text
            ]
        ]);
    }

    public function todayPayments(Request $request)
    {
        $search = $request->input('search');

        $query = Payment::with(['booking.customer', 'customer'])
            ->today()
            ->completed()
            ->latest();

        // Apply search filter
        if ($search) {
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(12);
        
        // Preserve search parameter in pagination
        $payments->appends($request->only('search'));
        
        $totalAmount = Payment::today()->completed()->sum('amount');

        return view('Dashboard.payments.today', compact('payments', 'totalAmount'));
    }

    public function pending(Request $request)
    {
        $search = $request->input('search');

        $query = Booking::with(['customer', 'bookingItems.service', 'bookingItems.designCatalog'])
            ->where('remaining_amount', '>', 0)
            ->whereNotNull('remaining_amount');

        // Apply search filter if search term exists
        if ($search) {
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(12);
        
        // Preserve search parameter in pagination
        $bookings->appends($request->only('search'));

        return view('Dashboard.payments.pending', compact('bookings'));
    }
}