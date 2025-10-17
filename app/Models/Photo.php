<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $primaryKey = 'photo_id';

    protected $fillable = [
        'announcement_id',
        'filename',
        'original_name',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'is_primary',
        'sort_order',
    ];

    protected $appends = ['url'];

    protected $casts = [
        'is_primary' => 'boolean',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the announcement that owns the photo.
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class, 'announcement_id', 'announcement_id');
    }

    public function getUrlAttribute(): string
    {
        // Получаем значение из колонки 'path'
        $path = $this->attributes['path'];

        // Если это уже полный URL (например, от Cloudinary в старых записях), возвращаем его как есть
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Иначе, формируем полный URL через фасад Storage
        return Storage::disk('public')->url($path);
    }

    /**
     * Scope a query to only include primary photos.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get the full URL for the photo.
     */
    public function getPathAttribute($value)
    {
        // Если путь уже является полным URL (например, из сидера), возвращаем как есть
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        // Иначе, формируем URL через Storage
        return Storage::disk('public')->url($value);
    }

    /**
     * Get the thumbnail URL for the photo.
     */
    public function getThumbnailUrlAttribute(): string
    {
        $pathInfo = pathinfo($this->path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        return asset('storage/' . $thumbnailPath);
    }
}
