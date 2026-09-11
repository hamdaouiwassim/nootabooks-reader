<?php

namespace App\Models;

use App\Models\Concerns\FlushesAppCache;
use App\Models\Concerns\GeneratesUniqueSlug;
use App\Models\Concerns\ResolvesUploadedFileUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class Book extends Model
{
    use HasFactory, GeneratesUniqueSlug, ResolvesUploadedFileUrl, FlushesAppCache;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function slugSource(): string
    {
        return 'title';
    }

    protected $fillable = [
        'category_id',
        'writer_id',
        'title',
        'slug',
        'description_short',
        'description',
        'cover_image',
        'is_coming_soon',
        'status',
        'file_path',
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
            'is_coming_soon' => 'boolean',
            'pages_count' => 'integer',
            'published_year' => 'integer',
            'file_size_mb' => 'decimal:2',
            'downloads_count' => 'integer',
            'rating_average' => 'decimal:1',
            'rating_count' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
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

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    public function recalculateRating(): void
    {
        $this->rating_average = round($this->reviews()->avg('rating') ?? 0, 1);
        $this->rating_count = $this->reviews()->count();
        $this->saveQuietly();
    }

    protected function coverImageUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveFileUrl($this->cover_image));
    }

    protected function coverImageSmUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveSmallVariantUrl($this->cover_image));
    }

    protected function coverImageMdUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveMediumVariantUrl($this->cover_image));
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveFileUrl($this->file_path));
    }

    /**
     * A short-lived signed URL for inline reading, generated fresh on every
     * call so the real storage path is never exposed to the client.
     */
    public function streamUrl(): ?string
    {
        return $this->file_path
            ? URL::temporarySignedRoute('books.stream', now()->addHours(2), ['book' => $this])
            : null;
    }

    /**
     * A short-lived signed URL for downloading, generated fresh on every
     * call so the real storage path is never exposed to the client.
     */
    public function downloadUrl(): ?string
    {
        return $this->file_path
            ? URL::temporarySignedRoute('books.download', now()->addMinutes(30), ['book' => $this])
            : null;
    }
}
