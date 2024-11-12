<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $primaryKey = 'record_id';
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'symptoms',
        'diagnosis',
        'medical_action',
        'lab_results',
        'treatment_date',
        'notes',
        'status',
        'follow_up_date'
    ];

    protected $casts = [
        'treatment_date' => 'datetime',
        'follow_up_date' => 'datetime'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'record_id');
    }
}