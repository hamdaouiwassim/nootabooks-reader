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
     * Resolves a "-{suffix}" thumbnail variant generated alongside the
     * full-size upload (see ImageOptimizer::optimizeResponsive()), so
     * grid/card/avatar contexts don't download a full-size image just to
     * shrink it in the browser. Falls back to the full-size URL when no such
     * variant exists — true for seeded demo assets and any upload made
     * before that variant was introduced.
     */
    protected function resolveVariantUrl(?string $value, string $suffix): ?string
    {
        if (! $value) {
            return null;
        }

        $variant = preg_replace('/(\.\w+)$/', "-{$suffix}\$1", $value);

        return $this->variantExists($variant) ? $this->resolveFileUrl($variant) : $this->resolveFileUrl($value);
    }

    protected function resolveSmallVariantUrl(?string $value): ?string
    {
        return $this->resolveVariantUrl($value, 'sm');
    }

    private function variantExists(string $variant): bool
    {
        $marker = '/storage/';

        if (($position = strpos($variant, $marker)) !== false) {
            return Storage::disk('public')->exists(substr($variant, $position + strlen($marker)));
        }

        if (str_starts_with($variant, 'storage/')) {
            return Storage::disk('public')->exists(substr($variant, strlen('storage/')));
        }

        return file_exists(public_path($variant));
    }
}
