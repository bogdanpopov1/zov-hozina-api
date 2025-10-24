<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SearchLogPhoto extends Model
{
    use HasFactory;

    protected $primaryKey = 'photo_id';

    protected $fillable = [
        'log_id',
        'path',
        'filename',
        'original_name',
        'mime_type',
        'size',
    ];

    protected $appends = ['url'];

    /**
     * Get the log that the photo belongs to.
     */
    public function searchLog(): BelongsTo
    {
        return $this->belongsTo(SearchLog::class, 'log_id', 'log_id');
    }

    /**
     * Get the full URL for the photo.
     */
    public function getUrlAttribute(): string
    {
        $path = $this->attributes['path'];

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}