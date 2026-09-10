<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class OptimizeStaticAssetImages extends Command
{
    protected $signature = 'images:optimize-assets';

    protected $description = 'Compress the static hero/banner images in public/assets/images and generate a small mobile variant of each (responsive backgrounds)';

    /**
     * Converted to WebP, both a large (desktop) and small (mobile) variant,
     * with every CSS reference already updated to match (see auth.css /
     * style.css: the mobile media query uses the "-sm" filename). Not used
     * anywhere as a <img>, alt-text image, or Open Graph fallback — pure CSS
     * backgrounds only.
     */
    private const RESPONSIVE_WEBP = [
        'discover-section.jpg' => ['large' => [1200, 900], 'small' => [800, 600]],
        'subscribe-section.jpg' => ['large' => [1200, 900], 'small' => [800, 600]],
        'login-hero.jpg' => ['large' => [1400, 1600], 'small' => [800, 900]],
        'register-hero.jpg' => ['large' => [1400, 1600], 'small' => [800, 900]],
        'admin-login-hero.jpg' => ['large' => [1400, 1600], 'small' => [800, 900]],
    ];

    /**
     * Kept as JPG at its base filename (not renamed) because it also doubles
     * as the site-wide Open Graph image fallback (see
     * partials/seo-meta.blade.php) — some link-preview crawlers still render
     * WebP inconsistently, so the large variant is only resized/recompressed
     * in place. The small variant is WebP and only ever used as a mobile CSS
     * background, never as the OG image, so it's safe to convert.
     */
    private const RESPONSIVE_JPEG_BASE = [
        'hero-section.jpg' => ['large' => [1920, 900], 'small' => [800, 500]],
    ];

    public function handle(): int
    {
        $directory = public_path('assets/images');

        foreach (self::RESPONSIVE_WEBP as $filename => $sizes) {
            $this->convertToResponsiveWebp($directory, $filename, $sizes);
        }

        foreach (self::RESPONSIVE_JPEG_BASE as $filename => $sizes) {
            $this->recompressWithSmallWebpVariant($directory, $filename, $sizes);
        }

        return self::SUCCESS;
    }

    private function convertToResponsiveWebp(string $directory, string $filename, array $sizes): void
    {
        $source = $directory.'/'.$filename;

        if (! is_file($source)) {
            $this->warn("Skipped {$filename}: not found");

            return;
        }

        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $before = filesize($source);

        $largeTarget = $directory."/{$baseName}.webp";
        $this->encodeWebp($source, $largeTarget, $sizes['large']);

        $smallTarget = $directory."/{$baseName}-sm.webp";
        $this->encodeWebp($source, $smallTarget, $sizes['small']);

        unlink($source);

        $this->line(sprintf(
            '%s -> %s.webp + %s-sm.webp  (%s KB -> %s KB large / %s KB small)',
            $filename,
            $baseName,
            $baseName,
            number_format($before / 1024),
            number_format(filesize($largeTarget) / 1024),
            number_format(filesize($smallTarget) / 1024)
        ));
    }

    private function recompressWithSmallWebpVariant(string $directory, string $filename, array $sizes): void
    {
        $source = $directory.'/'.$filename;

        if (! is_file($source)) {
            $this->warn("Skipped {$filename}: not found");

            return;
        }

        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $before = filesize($source);

        // Large: recompressed in place, same filename, stays JPEG (OG fallback).
        $image = (new ImageManager(new Driver()))->read($source);
        $image->scaleDown(width: $sizes['large'][0], height: $sizes['large'][1]);
        file_put_contents($source, (string) $image->toJpeg(quality: 80));

        // Small: WebP, mobile-only CSS background — never used as the OG image.
        $smallTarget = $directory."/{$baseName}-sm.webp";
        $this->encodeWebp($source, $smallTarget, $sizes['small']);

        $this->line(sprintf(
            '%s recompressed in place + %s-sm.webp generated  (%s KB -> %s KB large / %s KB small)',
            $filename,
            $baseName,
            number_format($before / 1024),
            number_format(filesize($source) / 1024),
            number_format(filesize($smallTarget) / 1024)
        ));
    }

    private function encodeWebp(string $sourcePath, string $targetPath, array $maxDimensions): void
    {
        $image = (new ImageManager(new Driver()))->read($sourcePath);
        $image->scaleDown(width: $maxDimensions[0], height: $maxDimensions[1]);

        file_put_contents($targetPath, (string) $image->toWebp(quality: 80));
    }
}
