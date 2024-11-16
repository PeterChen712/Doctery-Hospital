<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

use Notification;


class User extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'profile_image',
        'phone_number',
        'address',
        'is_active',
        'auth_provider',
        'auth_provider_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login' => 'datetime'
    ];

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'user_id');
    }

    public function customNotifications()
    {
        return $this->hasMany(UserNotification::class, 'user_id', 'user_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id', 'user_id');
    }
}