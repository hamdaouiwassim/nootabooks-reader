<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

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
            // Upgrade to https outside local dev: this app is served over
            // https in production, and an http:// URL embedded in an https://
            // page (e.g. the PDF reader's <iframe>) gets blocked outright as
            // mixed content. Left alone locally, where nothing serves https.
            str_starts_with($value, 'http://') => force_https_url($value),
            str_starts_with($value, 'https://') => $value,
            default => asset($value),
        };
    }

    /**
     * Resolves the small "-sm" card-thumbnail variant generated alongside
     * the full-size upload (see ImageOptimizer::optimizeResponsive()), so
     * grid/card contexts don't download a full-size image just to shrink it
     * in the browser. Falls back to the full-size URL when no small variant
     * exists — true for seeded demo assets and any upload made before this
     * feature existed.
     */
    protected function resolveSmallVariantUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $small = preg_replace('/(\.\w+)$/', '-sm$1', $value);

        return $this->smallVariantExists($small) ? $this->resolveFileUrl($small) : $this->resolveFileUrl($value);
    }

    private function smallVariantExists(string $small): bool
    {
        $marker = '/storage/';

        if (($position = strpos($small, $marker)) !== false) {
            return Storage::disk('public')->exists(substr($small, $position + strlen($marker)));
        }

        if (str_starts_with($small, 'storage/')) {
            return Storage::disk('public')->exists(substr($small, strlen('storage/')));
        }

        return file_exists(public_path($small));
    }
}
