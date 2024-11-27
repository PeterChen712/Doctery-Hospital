<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Medicine extends Model
{
use HasFactory;

    protected $primaryKey = 'medicine_id';

    protected $fillable = [
        'name',
        'description',
        'type',
        'stock',
        'price',
        'image',
        'expiry_date',
        'manufacturer',
        'category',
        'is_available'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    protected $appends = ['image_url'];

    // Relationships
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'medicine_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
            ->where('stock', '>', 0)
            ->whereDate('expiry_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('expiry_date', '<', now());
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<=', $threshold);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors & Mutators
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return 'data:image/jpeg;base64,' . base64_encode($this->image);
        }
        return null;
    }

    // Helper Methods
    public function isExpired()
    {
        return $this->expiry_date->isPast();
    }

    public function isLowStock($threshold = 10)
    {
        return $this->stock <= $threshold;
    }

    public function hasStock()
    {
        return $this->stock > 0;
    }

    public function decreaseStock($quantity = 1)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            $this->is_available = $this->stock > 0;
            $this->save();
            return true;
        }
        return false;
    }

    public function increaseStock($quantity = 1)
    {
        $this->increment('stock', $quantity);
        $this->is_available = true;
        $this->save();
        return true;
    }

    public function updateAvailability()
    {
        $this->is_available = $this->stock > 0 && !$this->isExpired();
        $this->save();
    }

    // Boot method for model events
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($medicine) {
            $medicine->is_available = $medicine->stock > 0 && !$medicine->isExpired();
        });
    }
}