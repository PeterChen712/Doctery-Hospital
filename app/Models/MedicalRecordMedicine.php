<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecordMedicine extends Model
{
    protected $table = 'medical_record_medicines';

    protected $fillable = [
        'medical_record_id',
        'medicine_id',
        'quantity',
        'dosage',
        'instructions'
    ];

    /**
     * Get the medical record that owns the prescription
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id', 'record_id');
    }

    /**
     * Get the medicine associated with the prescription
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'medicine_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key for the model.
     * 
     * @var array
     */
    protected $primaryKey = ['medical_record_id', 'medicine_id'];

    /**
     * Set the keys for a save update query.
     */
    protected function setKeysForSaveQuery($query)
    {
        foreach ($this->primaryKey as $pk) {
            $query->where($pk, $this->getAttribute($pk));
        }
        return $query;
    }
}