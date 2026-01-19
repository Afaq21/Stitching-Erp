<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'amount',
        'payment_type',
        'payment_method',
        'payment_date',
        'reference_number',
        'notes',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date'
    ];

    // Payment Type Constants
    const TYPE_ADVANCE = 'advance';
    const TYPE_PARTIAL = 'partial';
    const TYPE_FINAL = 'final';
    const TYPE_REFUND = 'refund';

    // Payment Method Constants
    const METHOD_CASH = 'cash';
    const METHOD_CARD = 'card';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_ONLINE = 'online';
    const METHOD_CHEQUE = 'cheque';

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getPaymentTypes()
    {
        return [
            self::TYPE_ADVANCE => 'Advance Payment',
            self::TYPE_PARTIAL => 'Partial Payment',
            self::TYPE_FINAL => 'Final Payment',
            self::TYPE_REFUND => 'Refund'
        ];
    }

    public static function getPaymentMethods()
    {
        return [
            self::METHOD_CASH => 'Cash',
            self::METHOD_CARD => 'Card',
            self::METHOD_BANK_TRANSFER => 'Bank Transfer',
            self::METHOD_ONLINE => 'Online Payment',
            self::METHOD_CHEQUE => 'Cheque'
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled'
        ];
    }

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Accessors
    public function getPaymentTypeTextAttribute()
    {
        return self::getPaymentTypes()[$this->payment_type] ?? 'Unknown';
    }

    public function getPaymentMethodTextAttribute()
    {
        return self::getPaymentMethods()[$this->payment_method] ?? 'Unknown';
    }

    public function getStatusTextAttribute()
    {
        return self::getStatuses()[$this->status] ?? 'Unknown';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_FAILED => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-secondary'
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('payment_date', today());
    }
}