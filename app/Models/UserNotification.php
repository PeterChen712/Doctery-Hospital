<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UserNotification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'data',
        'type',
        'notifiable_type',
        'notifiable_id',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'data' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread(Builder $query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead(Builder $query)
    {
        return $query->whereNotNull('read_at');
    }

    // Helper Methods
    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }

    public function isRead()
    {
        return $this->read_at !== null;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
