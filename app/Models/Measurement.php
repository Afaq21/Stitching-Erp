<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'service_id'];

    // Link to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Link to Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Link to MeasurementValues
    public function values()
    {
        return $this->hasMany(MeasurementValue::class);
    }
}

