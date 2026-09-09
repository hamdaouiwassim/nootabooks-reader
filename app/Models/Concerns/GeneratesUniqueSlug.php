<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait GeneratesUniqueSlug
{
    protected static function bootGeneratesUniqueSlug(): void
    {
        static::saving(function ($model) {
            if (blank($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->{$model->slugSource()});
            }
        });
    }

    protected static function generateUniqueSlug(string $source): string
    {
        // Default Str::slug() transliterates to ASCII, which strips Arabic
        // (and other non-Latin scripts) down to an empty string. Retrying
        // with a null $language skips that transliteration and keeps the
        // original letters, so an Arabic title gets an Arabic slug instead
        // of falling through to a random one.
        $base = Str::slug($source) ?: Str::slug($source, '-', null);
        $base = $base ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
