<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $table = 'schedules'; 
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'schedule_date',
        'start_time', 
        'end_time',
        'max_patients',
        'is_active'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Accessors
    public function getDayNameAttribute()
    {
        return [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ][$this->day_of_week];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    // Helper Methods
    public function isAvailable(Carbon $date)
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->day_of_week != $date->dayOfWeek) {
            return false;
        }

        $appointmentsCount = $this->appointments()
            ->whereDate('appointment_date', $date)
            ->count();

        return $appointmentsCount < $this->max_patients;
    }

    public function getTimeSlots($interval = 30)
    {
        $slots = [];
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes($interval);
        }

        return $slots;
    }
}