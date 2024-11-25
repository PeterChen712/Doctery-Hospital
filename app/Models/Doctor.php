<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $primaryKey = 'doctor_id';
    protected $fillable = [
        'user_id',
        'specialization',
        'license_number',
        'education',
        'experience',
        'consultation_fee',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'consultation_fee' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'doctor_id', 'doctor_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'doctor_id');
    }
}
