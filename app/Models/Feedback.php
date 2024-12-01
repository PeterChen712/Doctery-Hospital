<?php
// app/Models/Feedback.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'feedback_id';
    protected $table = 'feedback';

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'doctor_id',
        'overall_rating',
        'doctor_rating',
        'service_rating',
        'facility_rating',
        'staff_rating',
        'review',
        'doctor_response',
        'improvement_suggestions',
        'category',
        'is_public',
        'is_anonymous',
        'status',
        'admin_notes',
        'follow_up_action'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_anonymous' => 'boolean',
        'reviewed_at' => 'datetime'
    ];

    // Relationships
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id', 'record_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}