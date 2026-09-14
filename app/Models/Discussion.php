<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discussion extends Model
{
    use HasFactory;

    /**
     * Below this many characters a post reads as a one-word/thin reaction
     * rather than a real discussion — not worth its own SEO landing page.
     * Single source of truth for both the DB-level scope (list/sitemap
     * contexts) and the single-record check (individual discussion page).
     */
    public const MIN_MEANINGFUL_LENGTH = 20;

    protected $fillable = [
        'user_id',
        'book_id',
        'club_id',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'discussion_likes')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(DiscussionComment::class);
    }

    public function topLevelComments(): HasMany
    {
        return $this->comments()->whereNull('parent_id');
    }

    /**
     * Real, substantial posts worth surfacing as their own indexable page —
     * excludes one-word/thin reactions. There is no draft/private/moderation
     * status on discussions (every row is already a published public post),
     * so post length is the only meaningful-content signal available.
     */
    public function scopeIndexable(Builder $query): Builder
    {
        return $query->whereRaw('CHAR_LENGTH(TRIM(body)) >= ?', [self::MIN_MEANINGFUL_LENGTH]);
    }

    public function isIndexable(): bool
    {
        return mb_strlen(trim($this->body)) >= self::MIN_MEANINGFUL_LENGTH;
    }
}
