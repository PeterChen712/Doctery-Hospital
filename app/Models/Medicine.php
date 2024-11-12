<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $primaryKey = 'medicine_id';
    protected $fillable = [
        'name',
        'description',
        'type',
        'price',
        'stock',
        'image_url',
        'is_available',
        'expiry_date',
        'manufacturer',
        'category'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'expiry_date' => 'date'
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'medicine_id');
    }
}