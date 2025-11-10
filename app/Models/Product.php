<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Review;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'brand_id',
    ];

    protected $appends = [
        'image_url',
        'average_rating',
        'reviews_count',
    ];

    /** Het product hoort bij een categorie. */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Elk product heeft één merk. */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** Tags koppelen trefwoorden aan dit product. */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /** Reviews tonen feedback van klanten. */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return Storage::disk('public')->url(ltrim($this->image, '/'));
    }

    public function getAverageRatingAttribute(): ?float
    {
        if (! $this->relationLoaded('reviews')) {
            return $this->reviews()->where('is_approved', true)->avg('rating');
        }

        $approved = $this->reviews->where('is_approved', true);

        if ($approved->isEmpty()) {
            return null;
        }

        return round($approved->avg('rating'), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        if (! $this->relationLoaded('reviews')) {
            return (int) $this->reviews()->where('is_approved', true)->count();
        }

        return $this->reviews->where('is_approved', true)->count();
    }
}
