<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The primary key associated with the table.
     * Указываем Laravel, что наш первичный ключ называется 'user_id', а не 'id'.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'bio',
        'avatar',
        'location',
        'latitude',
        'longitude',
        'is_volunteer',
        'is_verified',
        'last_seen',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_volunteer' => 'boolean',
        'is_verified' => 'boolean',
        'last_seen' => 'datetime',
        'preferences' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the announcements for the user.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'user_id', 'user_id');
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id', 'user_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id', 'user_id');
    }

    /**
     * Get the favorites for the user.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'user_id', 'user_id');
    }

    /**
     * Get the reviews written by the user.
     */
    public function reviewsWritten(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id', 'user_id');
    }

    /**
     * Get the reviews about the user.
     */
    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewee_id', 'user_id');
    }

    /**
     * Get the volunteer subscriptions for the user.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(VolunteerSubscription::class, 'user_id', 'user_id');
    }

    /**
     * Get the search logs created by the user (as a volunteer).
     */
    public function searchLogs(): HasMany
    {
        return $this->hasMany(SearchLog::class, 'user_id', 'user_id');
    }
}