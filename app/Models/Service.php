<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'service_category',
        'price',
    ];

    // Relationship with MeasurementAttributes
    public function measurementAttributes()
    {
        return $this->hasMany(MeasurementAttribute::class);
    }

    // Relationship with DesignCatalogs
    public function designCatalogs()
    {
        return $this->hasMany(DesignCatalog::class);
    }
}



