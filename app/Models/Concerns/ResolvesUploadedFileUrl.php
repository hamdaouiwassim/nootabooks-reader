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
            // Upgrade to https regardless of what was stored: this app is
            // always served over https in production, and an http:// URL
            // embedded in an https:// page (e.g. the PDF reader's <iframe>)
            // gets blocked outright as mixed content.
            str_starts_with($value, 'http://') => 'https://'.substr($value, strlen('http://')),
            str_starts_with($value, 'https://') => $value,
            default => asset($value),
        };
    }
}
