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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'series_id',
        'series_order',
        'writer_id',
        'title',
        'title_en',
        'slug',
        'description_short',
        'description',
        'seo_title',
        'seo_description',
        'cover_image',
        'cover_image_md',
        'cover_image_sm',
        'is_coming_soon',
        'download_disabled',
        'reading_disabled',
        'status',
        'file_path',
        'pages_count',
        'language',
        'published_year',
        'file_size_mb',
        'tags',
        'downloads_count',
        'rating_average',
        'rating_count',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_coming_soon' => 'boolean',
            'download_disabled' => 'boolean',
            'reading_disabled' => 'boolean',
            'series_order' => 'integer',
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

    /**
     * The book's main/primary category — drives the breadcrumb, JSON-LD
     * genre, type_label, and admin table column. Always also present in
     * categories() below; see book_category migration.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The full set of categories this book is tagged with (primary +
     * whatever extra ones the admin picked) — used for discover filtering,
     * category-page listings, and the category chips on the book page.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function writer(): BelongsTo
    {
        return $this->belongsTo(Writer::class);
    }

    /**
     * The series this book is part of ("الجزء 1", "الجزء 2", ...), if any —
     * see Series::books() for the ordered sibling list.
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    public function bookmarkedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_bookmarks')->withTimestamps();
    }

    public function recalculateRating(): void
    {
        $this->rating_average = round($this->reviews()->avg('rating') ?? 0, 1);
        $this->rating_count = $this->reviews()->count();
        $this->saveQuietly();
    }

    /**
     * "رواية" for books in the novels category, "كتاب" otherwise — used to
     * generate SEO copy that matches how people actually search, without
     * calling every book a "رواية".
     */
    protected function typeLabel(): Attribute
    {
        return Attribute::make(get: fn () => $this->category?->slug === 'novels' ? 'رواية' : 'كتاب');
    }

    /**
     * Falls back to a generated, search-intent title (download + read) when
     * the admin hasn't set a manual seo_title override.
     */
    protected function resolvedSeoTitle(): Attribute
    {
        return Attribute::make(get: function () {
            if ($this->seo_title) {
                return $this->seo_title;
            }

            $readingSuffix = $this->type_label === 'رواية' ? 'وقراءتها أونلاين' : 'وقراءته أونلاين';

            return "تحميل {$this->type_label} {$this->title} PDF {$readingSuffix} | نوته بوك";
        });
    }

    /**
     * Falls back to a generated meta description when the admin hasn't set
     * a manual seo_description override — leads with the actual
     * download/read search intent, then folds in the book's own
     * description when one exists so the result stays unique per book
     * rather than a purely templated sentence.
     */
    protected function resolvedSeoDescription(): Attribute
    {
        return Attribute::make(get: function () {
            if ($this->seo_description) {
                return $this->seo_description;
            }

            $readingSuffix = $this->type_label === 'رواية' ? 'وقراءتها أونلاين' : 'وقراءته أونلاين';
            $intro = "تحميل {$this->type_label} {$this->title} PDF {$readingSuffix}.";

            $summary = $this->description_short
                ?: ($this->description ? \Illuminate\Support\Str::limit(strip_tags($this->description), 120) : null);

            if ($summary) {
                return \Illuminate\Support\Str::limit("{$intro} {$summary}", 160);
            }

            $ofSuffix = $this->type_label === 'رواية' ? 'الرواية ومؤلفها وتصنيفها' : 'الكتاب ومؤلفه وتصنيفه';

            return \Illuminate\Support\Str::limit("{$intro} تعرّف على {$ofSuffix} واقرأه مباشرة على نوته بوك.", 160);
        });
    }

    protected function coverImageUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveFileUrl($this->cover_image));
    }

    /**
     * The admin only uploads one cover (compressed to 300×450 — see
     * Admin\BookController::storeCoverVariant()); cover_image_md/_sm are
     * legacy columns from an old multi-size pipeline and are normally empty
     * for any book saved since, so this just falls back to the single cover.
     */
    protected function coverImageSmUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveFileUrl($this->cover_image_sm) ?? $this->resolveFileUrl($this->cover_image));
    }

    protected function coverImageMdUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveFileUrl($this->cover_image_md) ?? $this->resolveFileUrl($this->cover_image));
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
        return ($this->file_path && ! $this->download_disabled)
            ? URL::temporarySignedRoute('books.download', now()->addMinutes(30), ['book' => $this])
            : null;
    }

    /**
     * Real counts of books added per day for the last $days days, including
     * days with zero additions (so a chart's x-axis stays a continuous
     * timeline) — mirrors DownloadLog::perDay().
     */
    public static function perDay(int $days): array
    {
        $counts = static::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->groupBy('day')
            ->pluck('total', 'day');

        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->format('Y-m-d');
            $result[] = [
                'label' => $date->format('j'),
                'fullLabel' => $date->translatedFormat('j M'),
                'count' => (int) ($counts[$key] ?? 0),
            ];
        }

        return $result;
    }
}
