<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'gender',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }

    // Payment Summary Methods
    public function getTotalPaidAttribute()
    {
        return $this->payments()->completed()->sum('amount');
    }

    public function getTotalPendingAttribute()
    {
        return $this->bookings()->sum('total_amount') - $this->total_paid;
    }
}


