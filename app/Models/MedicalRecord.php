<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MedicalRecord extends Model
{
    protected $primaryKey = 'record_id';

    const STATUS_ONGOING = 'ONGOING';
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'creator_doctor_id',
        'symptoms',
        'diagnosis',
        'medical_action',
        'lab_results',
        'treatment_date',
        'notes',
        'status',
        'follow_up_date',
        'needs_follow_up'
    ];

    protected $casts = [
        'treatment_date' => 'datetime',
        'follow_up_date' => 'datetime',
        'status' => 'string',
        'needs_follow_up' => 'boolean'
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

    public function creator()
    {
        return $this->belongsTo(Doctor::class, 'creator_doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'record_id');
    }

    public function medicines()
    {
        return $this->belongsToMany(
            Medicine::class,
            'medical_record_medicines',
            'medical_record_id',
            'medicine_id'
        )
            ->withPivot(['quantity', 'dosage', 'instructions'])
            ->withTimestamps();
    }

    public function notifications()
    {
        return $this->morphMany(UserNotification::class, 'notifiable');
    }

    // Scopes
    public function scopeOngoing(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_ONGOING, self::STATUS_IN_PROGRESS]);
    }

    public function scopeNeedsFollowUp(Builder $query): Builder
    {
        return $query->where('needs_follow_up', true)
                    ->where('status', '!=', self::STATUS_COMPLETED);
    }

    public function scopeForDoctor(Builder $query, $doctorId): Builder
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderByDesc('treatment_date');
    }

    // Helper methods
    public function isOngoing(): bool
    {
        return in_array($this->status, [self::STATUS_ONGOING, self::STATUS_IN_PROGRESS]);
    }

    public function requiresFollowUp(): bool
    {
        return $this->needs_follow_up && $this->status !== self::STATUS_COMPLETED;
    }
}