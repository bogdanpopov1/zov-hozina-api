<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SearchLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'log_id';

    protected $fillable = [
        'announcement_id',
        'user_id',
        'comment',
        'latitude',
        'longitude',
    ];

    /**
     * Get the announcement that the log belongs to.
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class, 'announcement_id', 'announcement_id');
    }

    /**
     * Get the user (volunteer) that created the log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the photos for the search log.
     * Note: The SearchLogPhoto model will be created in the next step.
     */
    public function photos(): HasMany
    {
        // This relationship will point to the model we create in the next step
        return $this->hasMany(SearchLogPhoto::class, 'log_id', 'log_id');
    }
}