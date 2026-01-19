<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'title',
        'description',
        'image_path',
        'price_adjustment',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_adjustment' => 'decimal:2'
    ];

    // Relationship with Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}