<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait GeneratesUniqueSlug
{
    protected static function bootGeneratesUniqueSlug(): void
    {
        static::saving(function ($model) {
            $sourceField = $model->slugSource();

            // Regenerate on a blank slug (new record), or when the source
            // field (title/name) was just edited on an existing one — so
            // renaming a book in the admin keeps its slug matching the new
            // title instead of it staying frozen at whatever it was first
            // created with.
            if (blank($model->slug) || ($model->exists && $model->isDirty($sourceField))) {
                $model->slug = static::generateUniqueSlug($model->{$sourceField}, $model);
            }
        });
    }

    protected static function generateUniqueSlug(string $source, ?self $ignoreModel = null): string
    {
        // Str::slug()'s default ASCII transliteration (voku/portable-ascii)
        // actually ships a Buckwalter-style Arabic->Latin romanization table
        // (e.g. ب -> b, ث -> th, ع -> `), so it never returns empty for
        // Arabic text — it silently produces a mangled phonetic transliteration
        // instead. Detect Arabic explicitly and skip transliteration for it
        // (passing a null $language keeps the original letters), rather than
        // relying on "empty output" as the signal, since that never fires.
        $base = preg_match('/\p{Arabic}/u', $source)
            ? Str::slug($source, '-', null)
            : Str::slug($source);
        $base = $base ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        // Exclude the model's own current row — otherwise a title edit that
        // slugifies to the same value it already had (e.g. only casing or
        // whitespace changed) would see its own row as "taken" and get
        // needlessly suffixed to "-2".
        while (
            static::where('slug', $slug)
                ->when($ignoreModel?->exists, fn ($q) => $q->where('id', '!=', $ignoreModel->id))
                ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
