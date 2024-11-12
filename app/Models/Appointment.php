<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $primaryKey = 'appointment_id';
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'appointment_date',
        'status',
        'reason',
        'notes',
        'is_rescheduled'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'is_rescheduled' => 'boolean'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function schedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'schedule_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'appointment_id');
    }
}