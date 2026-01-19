<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'booking_date',
        'delivery_date',
        'status',
        'total_amount',
        'advance_amount',
        'remaining_amount',
        'notes',
        'priority'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_READY = 'ready';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PARTIAL = 'partial';
    const PAYMENT_COMPLETED = 'completed';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_READY => 'Ready',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled'
        ];
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function completedPayments()
    {
        return $this->hasMany(Payment::class)->completed();
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_CONFIRMED => 'bg-info',
            self::STATUS_IN_PROGRESS => 'bg-primary',
            self::STATUS_READY => 'bg-success',
            self::STATUS_DELIVERED => 'bg-dark',
            self::STATUS_CANCELLED => 'bg-danger'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusTextAttribute()
    {
        return self::getStatuses()[$this->status] ?? 'Unknown';
    }

    // Payment Status Methods (Updated to use payments table)
    public function getTotalPaidAttribute()
    {
        return $this->completedPayments()->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }

    public function getAdvanceAmountAttribute()
    {
        return $this->completedPayments()
            ->where('payment_type', Payment::TYPE_ADVANCE)
            ->sum('amount');
    }

    public function getPaymentStatusAttribute()
    {
        $totalPaid = $this->total_paid;
        
        if ($totalPaid >= $this->total_amount) {
            return self::PAYMENT_COMPLETED;
        } elseif ($totalPaid > 0) {
            return self::PAYMENT_PARTIAL;
        } else {
            return self::PAYMENT_PENDING;
        }
    }

    public function getPaymentStatusTextAttribute()
    {
        $statuses = [
            self::PAYMENT_PENDING => 'Payment Pending',
            self::PAYMENT_PARTIAL => 'Partial Payment',
            self::PAYMENT_COMPLETED => 'Payment Completed'
        ];
        return $statuses[$this->payment_status] ?? 'Unknown';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            self::PAYMENT_PENDING => 'bg-danger',
            self::PAYMENT_PARTIAL => 'bg-warning',
            self::PAYMENT_COMPLETED => 'bg-success'
        ];
        return $badges[$this->payment_status] ?? 'bg-secondary';
    }

    public function isPaymentComplete()
    {
        return $this->total_paid >= $this->total_amount;
    }

    public function canBeDelivered()
    {
        // Business rule: Can only deliver if payment is complete
        return $this->isPaymentComplete() && $this->status === self::STATUS_READY;
    }

    public function addPayment($amount, $paymentType = Payment::TYPE_ADVANCE, $paymentMethod = Payment::METHOD_CASH, $notes = null, $referenceNumber = null)
    {
        return $this->payments()->create([
            'customer_id' => $this->customer_id,
            'amount' => $amount,
            'payment_type' => $paymentType,
            'payment_method' => $paymentMethod,
            'payment_date' => now()->toDateString(),
            'reference_number' => $referenceNumber,
            'notes' => $notes,
            'status' => Payment::STATUS_COMPLETED
        ]);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('booking_date', today());
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('delivery_date', '>=', today());
    }
}