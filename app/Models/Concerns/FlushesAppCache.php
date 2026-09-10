<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

/**
 * Clears the cached home-page data and sitemap whenever a row of this model
 * is created, updated, or deleted, so pages built with Cache::remember()
 * never show stale books/writers/categories after an admin edit.
 *
 * Note: saveQuietly() (used by Book::recalculateRating()) intentionally
 * skips model events, so a review-driven rating change won't bust the cache
 * immediately — the cache TTL is the safety net for that case.
 */
trait FlushesAppCache
{
    protected static function bootFlushesAppCache(): void
    {
        static::saved(fn () => static::flushAppCache());
        static::deleted(fn () => static::flushAppCache());
    }

    protected static function flushAppCache(): void
    {
        Cache::forget('home.page.data');
        Cache::forget('sitemap.xml');
    }
}
