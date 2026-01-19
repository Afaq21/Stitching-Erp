<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement_id',
        'measurement_attribute_id',
        'value',
    ];

    // Relationship with MeasurementAttribute
    public function measurementAttribute()
    {
        return $this->belongsTo(MeasurementAttribute::class);
    }

    // Relationship with Measurement
    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    // Relationship with Customer (if you have customer model)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship with Order (if you have order model)

}