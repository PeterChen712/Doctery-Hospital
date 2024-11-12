<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $primaryKey = 'prescription_id';
    protected $fillable = [
        'record_id',
        'medicine_id',
        'quantity',
        'dosage',
        'instructions',
        'status',
        'valid_until'
    ];

    protected $casts = [
        'valid_until' => 'datetime'
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'record_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
}