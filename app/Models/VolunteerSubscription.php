<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerSubscription extends Model
{
    use HasFactory;

    protected $primaryKey = 'subscription_id';

    protected $fillable = [
        'user_id',
        'location_name',
        'latitude',
        'longitude',
        'radius',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}