<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{   
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'doctor_id',
        'schedule_date',
        'start_time',
        'end_time',
        'max_patients',
        'day_of_week',
        'is_available',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_available' => 'boolean'
    ];

    protected $appends = ['day_name', 'available_slots'];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'schedule_id', 'schedule_id');
    }

    // Accessors
    public function getDayNameAttribute()
    {
        return Carbon::parse($this->schedule_date)->format('l');
    }

    public function getAvailableSlotsAttribute()
    {
        return $this->max_patients - $this->appointments()->count();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('schedule_date', '>=', now()->startOfDay());
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                    ->where('schedule_date', '>=', now())
                    ->whereRaw('(SELECT COUNT(*) FROM appointments 
                              WHERE appointments.schedule_id = schedules.schedule_id) < schedules.max_patients');
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->is_available 
            && $this->schedule_date->isFuture() 
            && $this->appointments()->count() < $this->max_patients;
    }

    public function hasTimeSlotAvailable($time)
    {
        $appointmentTime = Carbon::parse($time);
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        return $appointmentTime->between($startTime, $endTime) 
            && $this->appointments()
                    ->where('appointment_time', $time)
                    ->count() < $this->max_patients;
    }

    public function getAvailableTimeSlots()
    {
        $slots = [];
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);
        
        while ($startTime < $endTime) {
            if ($this->hasTimeSlotAvailable($startTime->format('H:i'))) {
                $slots[] = $startTime->format('H:i');
            }
            $startTime->addMinutes(30); // 30-minute intervals
        }

        return $slots;
    }

    public function isFullyBooked()
    {
        return $this->appointments()->count() >= $this->max_patients;
    }

    public function getBookedPatientsCount()
    {
        return $this->appointments()->count();
    }

    public function canBeDeleted()
    {
        return $this->appointments()->count() === 0;
    }

    protected static function boot()
    {
        parent::boot();

        // Prevent deletion if schedule has appointments
        static::deleting(function($schedule) {
            if ($schedule->appointments()->count() > 0) {
                throw new \Exception('Cannot delete schedule with existing appointments.');
            }
        });
    }
}