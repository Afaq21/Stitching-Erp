<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'name'];

    // Link to Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Link to MeasurementValues
    public function measurementValues()
    {
        return $this->hasMany(MeasurementValue::class);
    }
}

