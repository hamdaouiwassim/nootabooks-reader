<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'category_id',
        'writer_id',
        'title',
        'slug',
        'description_short',
        'description',
        'cover_image',
        'pages_count',
        'language',
        'published_year',
        'file_size_mb',
        'formats',
        'tags',
        'downloads_count',
        'rating_average',
        'rating_count',
    ];

    protected function casts(): array
    {
        return [
            'formats' => 'array',
            'tags' => 'array',
            'pages_count' => 'integer',
            'published_year' => 'integer',
            'file_size_mb' => 'decimal:2',
            'downloads_count' => 'integer',
            'rating_average' => 'decimal:1',
            'rating_count' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function writer(): BelongsTo
    {
        return $this->belongsTo(Writer::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function recalculateRating(): void
    {
        $this->rating_average = round($this->reviews()->avg('rating') ?? 0, 1);
        $this->rating_count = $this->reviews()->count();
        $this->saveQuietly();
    }
}
