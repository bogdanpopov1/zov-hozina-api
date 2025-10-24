<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Announcement extends Model
{
    use HasFactory;

    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'user_id',
        'announcement_type',
        'category_id',
        'breed_id',
        'pet_name',
        'pet_type',
        'pet_breed',
        'description',
        'location_address',
        'latitude',
        'longitude',
        'status',
        'age',
        'gender',
        'size',
        'color',
        'is_vaccinated',
        'is_sterilized',
        'has_pedigree',
        'price',
        'price_type',
        'additional_info',
        'views_count',
        'favorites_count',
        'expires_at',
        'is_featured',
        'contact_info',
    ];

    protected $casts = [
        'is_vaccinated' => 'boolean',
        'is_sterilized' => 'boolean',
        'has_pedigree' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'views_count' => 'integer',
        'favorites_count' => 'integer',
        'expires_at' => 'datetime',
        'contact_info' => 'array',
    ];

    /**
     * Get the user that owns the announcement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the category that owns the announcement.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    /**
     * Get the breed that owns the announcement.
     */
    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class, 'breed_id', 'breed_id');
    }

    /**
     * Get the photos for the announcement.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class, 'announcement_id', 'announcement_id');
    }

    /**
     * Get the primary photo for the announcement.
     */
    public function primaryPhoto(): HasMany
    {
        return $this->hasMany(Photo::class, 'announcement_id', 'announcement_id')->where('is_primary', true);
    }

    /**
     * Get the messages for the announcement.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'announcement_id', 'announcement_id');
    }

    /**
     * Get the favorites for the announcement.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'announcement_id', 'announcement_id');
    }

    /**
     * Get the reviews for the announcement.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'announcement_id', 'announcement_id');
    }

    /**
     * Get the search logs for the announcement.
     */
    public function searchLogs(): HasMany
    {
        return $this->hasMany(SearchLog::class, 'announcement_id', 'announcement_id')->latest();
    }

    /**
     * Scope a query to only include active announcements.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured announcements.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to filter by breed.
     */
    public function scopeByBreed($query, $breedId)
    {
        return $query->where('breed_id', $breedId);
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope a query to filter by location.
     */
    public function scopeByLocation($query, $latitude, $longitude, $radius = 50)
    {
        return $query->whereRaw(
            "ST_Distance_Sphere(POINT(longitude, latitude), POINT(?, ?)) <= ?",
            [$longitude, $latitude, $radius * 1000]
        );
    }
}