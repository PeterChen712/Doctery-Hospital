<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'appointment_id';

    // Status constants
    const STATUS_PENDING = 'PENDING';
    const STATUS_PENDING_CONFIRMATION = 'PENDING_CONFIRMATION';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_COMPLETED = 'COMPLETED';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'appointment_date',
        'reason',
        'status',
        'notes',
        'symptoms',
        'is_rescheduled',
        'patient_confirmed',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_rescheduled' => 'boolean',
        'patient_confirmed' => 'boolean'
    ];

    // Relationships
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
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_appointments', 'appointment_id', 'schedule_id')
            ->withTimestamps();
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'appointment_id');
    }

    public function notifications()
    {
        return $this->morphMany(UserNotification::class, 'notifiable');
    }

    // Helper methods
    public function isUpcoming()
    {
        return $this->appointment_date->isFuture();
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPendingConfirmation()
    {
        return $this->status === self::STATUS_PENDING_CONFIRMATION;
    }

    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function canBeModified()
    {
        return !$this->isCancelled() && !$this->isCompleted() && $this->isUpcoming();
    }

    public function confirm()
    {
        $this->update([
            'status' => self::STATUS_CONFIRMED,
            'patient_confirmed' => true
        ]);
        
        $this->notifyParticipants('appointment_confirmed');
    }

    public function cancel()
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'patient_confirmed' => false
        ]);
        
        $this->notifyParticipants('appointment_cancelled');
    }

    public function complete()
    {
        $this->update(['status' => self::STATUS_COMPLETED]);
        $this->notifyParticipants('appointment_completed');
    }

    public function reschedule(Schedule $newSchedule)
    {
        $oldSchedule = $this->schedule;
        
        $this->update([
            'schedule_id' => $newSchedule->schedule_id,
            'appointment_date' => $newSchedule->schedule_date,
            'is_rescheduled' => true,
            'status' => self::STATUS_PENDING_CONFIRMATION
        ]);

        $this->schedules()->attach($newSchedule->schedule_id);
        $this->notifyParticipants('appointment_rescheduled');
    }

    // Notification helper
    protected function notifyParticipants($type)
    {
        // Notify patient
        if ($this->patient && $this->patient->user) {
            UserNotification::create([
                'user_id' => $this->patient->user->user_id,
                'title' => ucfirst(str_replace('_', ' ', $type)),
                'type' => 'APPOINTMENT',
                'data' => json_encode([
                    'appointment_id' => $this->appointment_id,
                    'status' => $this->status,
                    'date' => $this->appointment_date->format('Y-m-d H:i:s')
                ])
            ]);
        }

        // Notify doctor
        if ($this->doctor && $this->doctor->user) {
            UserNotification::create([
                'user_id' => $this->doctor->user->user_id,
                'title' => ucfirst(str_replace('_', ' ', $type)),
                'type' => 'APPOINTMENT',
                'data' => json_encode([
                    'appointment_id' => $this->appointment_id,
                    'status' => $this->status,
                    'date' => $this->appointment_date->format('Y-m-d H:i:s')
                ])
            ]);
        }
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePendingConfirmation($query)
    {
        return $query->where('status', self::STATUS_PENDING_CONFIRMATION);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>', Carbon::now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', Carbon::today());
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    // Validation rules
    public static function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,patient_id',
            'doctor_id' => 'nullable|exists:doctors,doctor_id',
            'schedule_id' => 'nullable|exists:schedules,schedule_id',
            'appointment_date' => 'required|date|after:now',
            'reason' => 'required|string|max:500',
            'symptoms' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:' . implode(',', [
                self::STATUS_PENDING,
                self::STATUS_PENDING_CONFIRMATION,
                self::STATUS_CONFIRMED,
                self::STATUS_CANCELLED,
                self::STATUS_COMPLETED
            ])
        ];
    }
}