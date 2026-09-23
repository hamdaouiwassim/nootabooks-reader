<?php

namespace App\Models;

use App\Models\Concerns\ResolvesUploadedFileUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Advertisement extends Model
{
    use ResolvesUploadedFileUrl;

    /**
     * The fixed set of placement zones ads can be assigned to, keyed by the
     * value stored in `zone` with an Arabic label for the admin <select>.
     * Deliberately a plain const (not a DB table or enum class) — matches
     * how the rest of this app validates fixed string sets inline (see
     * Book::status).
     */
    public const array ZONES = [
        'home_top' => 'أعلى الصفحة الرئيسية',
        'home_middle' => 'وسط الصفحة الرئيسية',
        'discover_sidebar' => 'الشريط الجانبي - صفحة الاستكشاف',
        'book_details_bottom' => 'أسفل صفحة تفاصيل الكتاب',
        'community_sidebar' => 'الشريط الجانبي - صفحة المجتمع',
        'global_footer' => 'أسفل كل صفحة (فوق التذييل)',
    ];

    public const array TYPES = [
        'image_banner' => 'بانر صورة',
        'image_text' => 'صورة ونص',
        'animated_banner' => 'بانر متحرك (GIF)',
    ];

    protected $fillable = [
        'name',
        'type',
        'zone',
        'creative_path',
        'target_url',
        'heading',
        'body_text',
        'alt_text',
        'is_active',
        'start_date',
        'end_date',
        'clicks_count',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
            'clicks_count' => 'integer',
        ];
    }

    protected function creativeUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->resolveFileUrl($this->creative_path));
    }

    /**
     * Unlike Book::downloadUrl()/streamUrl() (temporarySignedRoute, since
     * those gate access to a rights-sensitive file), this is a permanent
     * signed URL — an ad link is meant to sit in rendered/cached HTML
     * indefinitely, and there's nothing sensitive to time-box, only the
     * {ad} id to tamper-proof.
     */
    public function clickUrl(): string
    {
        return URL::signedRoute('ads.click', ['ad' => $this]);
    }

    /**
     * Active + currently within its (optional) date range. No existing
     * date-range-active idiom elsewhere in this app, so this is written
     * fresh, in the same plain-scope style as Book::scopePublished().
     */
    public function scopeActive(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('start_date')->orWhere('start_date', '<=', $today))
            ->where(fn (Builder $q) => $q->whereNull('end_date')->orWhere('end_date', '>=', $today));
    }

    public function scopeForZone(Builder $query, string $zone): Builder
    {
        return $query->where('zone', $zone);
    }
}
