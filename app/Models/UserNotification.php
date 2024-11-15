<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    // Specify the table name
    protected $table = 'notifications';

    // Specify the primary key
    protected $primaryKey = 'notification_id';

    // Primary key is auto-incrementing integer
    protected $keyType = 'int';
    public $incrementing = true;

    // Disable timestamps (we only have created_at)
    public $timestamps = false;

    // Fillable attributes
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'created_at', // Include if you might set it manually
    ];

    // Cast attributes
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Notification types
    const TYPE_APPOINTMENT = 'APPOINTMENT';
    const TYPE_PRESCRIPTION = 'PRESCRIPTION';
    const TYPE_GENERAL = 'GENERAL';

    /**
     * Relationship: Notification belongs to a User.
     */
    public function user()
    {
        // Adjust foreign and local keys if necessary
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Create a new notification.
     */
    public static function notify($userId, $title, $message, $type = self::TYPE_GENERAL)
    {
        return self::create([
            'user_id'    => $userId,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'is_read'    => false,
            'created_at' => now(),
        ]);
    }
}