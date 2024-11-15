<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $primaryKey = 'patient_id';
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'blood_type',
        'allergies',
        'medical_history',
        'emergency_contact'
    ];

    protected $casts = [
        'date_of_birth' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'patient_id');
    }

    public function prescriptions()
    {
        return $this->hasManyThrough(
            Prescription::class,
            MedicalRecord::class,
            'patient_id',     
            'medical_record_id', 
            'patient_id',      
            'record_id'
        );
    }

    
}
