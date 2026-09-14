<?php

namespace App\Models;

use App\Models\Concerns\FlushesAppCache;
use App\Models\Concerns\GeneratesUniqueSlug;
use App\Models\Concerns\ResolvesUploadedFileUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Writer extends Model
{
    use HasFactory, GeneratesUniqueSlug, ResolvesUploadedFileUrl, FlushesAppCache;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function slugSource(): string
    {
        return 'name';
    }

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'photo',
        'genre_tag',
        'bio',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'followers_count',
        'rating_average',
        'joined_year',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'followers_count' => 'integer',
            'rating_average' => 'decimal:1',
            'joined_year' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'following')->withTimestamps();
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveFileUrl($this->photo));
    }

    protected function photoSmUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveSmallVariantUrl($this->photo));
    }

    /**
     * The small circular avatar used in cards/grids (~80-90px) — noticeably
     * smaller than "sm" (300x300, sized for the ~160-200px featured/hero
     * photo contexts), so those tiny avatars aren't downloading 12x more
     * pixels than they display.
     */
    protected function photoXsUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveVariantUrl($this->photo, 'xs'));
    }
}
