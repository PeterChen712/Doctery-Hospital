<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Schedule extends Model
{
    use HasFactory;

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
        'schedule_date' => 'datetime',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'day_of_week' => 'integer',
        'is_available' => 'boolean',
        'max_patients' => 'integer'
    ];

    protected $appends = [
        'day_name', 
        'available_slots', 
        'booked_patients',
        'remaining_spaces'
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'schedule_appointments', 'schedule_id', 'appointment_id')
            ->withTimestamps();
    }

    // Accessors
    public function getDayNameAttribute()
    {
        return Carbon::parse($this->schedule_date)->format('l');
    }

    public function getAvailableSlotsAttribute()
    {
        return $this->max_patients - $this->booked_patients;
    }

    public function getBookedPatientsAttribute()
    {
        return $this->appointments()
            ->whereIn('status', ['CONFIRMED', 'PENDING_CONFIRMATION'])
            ->count();
    }

    public function getRemainingSpacesAttribute()
    {
        return max(0, $this->max_patients - $this->booked_patients);
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
                       INNER JOIN schedule_appointments ON appointments.appointment_id = schedule_appointments.appointment_id 
                       WHERE schedule_appointments.schedule_id = schedules.schedule_id 
                       AND appointments.status IN ("CONFIRMED", "PENDING_CONFIRMATION")) < schedules.max_patients');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('schedule_date', $date);
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->is_available
            && $this->schedule_date->isFuture()
            && $this->booked_patients < $this->max_patients;
    }

    public function hasTimeSlotAvailable($time)
    {
        $appointmentTime = Carbon::parse($time);
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        return $appointmentTime->between($startTime, $endTime)
            && $this->appointments()
                ->whereIn('status', ['CONFIRMED', 'PENDING_CONFIRMATION'])
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
            $startTime->addMinutes(30);
        }

        return $slots;
    }

    public function isFullyBooked()
    {
        return $this->booked_patients >= $this->max_patients;
    }

    public function canBeDeleted()
    {
        return $this->appointments()->count() === 0;
    }

    public function hasTimeConflict($startTime, $endTime)
    {
        return Schedule::where('doctor_id', $this->doctor_id)
            ->where('schedule_date', $this->schedule_date)
            ->where('schedule_id', '!=', $this->schedule_id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime]);
            })
            ->exists();
    }

    // Boot method for model events
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($schedule) {
            if (!$schedule->canBeDeleted()) {
                throw new \Exception('Cannot delete schedule with existing appointments.');
            }
        });

        static::saving(function ($schedule) {
            if ($schedule->start_time >= $schedule->end_time) {
                throw new \Exception('Start time must be before end time.');
            }
        });
    }

    // Validation rules
    public static function rules($doctorId = null)
    {
        return [
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'schedule_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'day_of_week' => 'required|integer|between:0,6',
            'is_available' => 'boolean'
        ];
    }
}