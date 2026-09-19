<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('app.settings'));
        static::deleted(fn () => Cache::forget('app.settings'));
    }

    /**
     * Read via the shared site footer on every page, so this must never take
     * the whole site down if the `settings` migration hasn't run yet (e.g.
     * code deployed slightly ahead of `php artisan migrate`) — fail closed
     * to "no settings" instead of a DB error.
     *
     * @return array<string, string>
     */
    protected static function cached(): array
    {
        return Cache::rememberForever('app.settings', function () {
            try {
                return static::query()->pluck('value', 'key')->all();
            } catch (QueryException) {
                return [];
            }
        });
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::cached()[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
