<?php

namespace App\Models\Concerns;

trait ResolvesUploadedFileUrl
{
    /**
     * Resolves a stored file path whether it's a full URL (files uploaded
     * through the admin panel, stored on the public disk) or a path relative
     * to this app's own public/ dir (the local static assets used by seeders).
     */
    protected function resolveFileUrl(?string $value): ?string
    {
        return match (true) {
            ! $value => null,
            str_starts_with($value, 'http://'),
            str_starts_with($value, 'https://') => $value,
            default => asset($value),
        };
    }
}
