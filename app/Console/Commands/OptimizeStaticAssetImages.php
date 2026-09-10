<?php

namespace App\Console\Commands;

use App\Services\Image\ImageOptimizer;
use Illuminate\Console\Command;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class OptimizeStaticAssetImages extends Command
{
    protected $signature = 'images:optimize-assets';

    protected $description = 'Compress the static hero/banner JPGs in public/assets/images, converting to WebP where nothing depends on the original file staying a JPG';

    /**
     * Converted to WebP in place, with every CSS reference already updated
     * to match (see auth.css / style.css). Not used anywhere: cover, alt-text
     * image, or Open Graph fallback — pure CSS backgrounds only.
     */
    private const CONVERT_TO_WEBP = [
        'discover-section.jpg' => ['maxWidth' => 1200, 'maxHeight' => 900],
        'subscribe-section.jpg' => ['maxWidth' => 1200, 'maxHeight' => 900],
        'login-hero.jpg' => ['maxWidth' => 1400, 'maxHeight' => 1600],
        'register-hero.jpg' => ['maxWidth' => 1400, 'maxHeight' => 1600],
    ];

    /**
     * Kept as JPG (not renamed) because it also doubles as the site-wide
     * Open Graph image fallback (see partials/seo-meta.blade.php) — some
     * link-preview crawlers still render WebP inconsistently, so this one
     * is only resized/recompressed in place, never renamed or converted.
     */
    private const RECOMPRESS_JPEG = [
        'hero-section.jpg' => ['maxWidth' => 1920, 'maxHeight' => 900],
    ];

    public function handle(ImageOptimizer $optimizer): int
    {
        $directory = public_path('assets/images');

        foreach (self::CONVERT_TO_WEBP as $filename => $dims) {
            $source = $directory.'/'.$filename;

            if (! is_file($source)) {
                $this->warn("Skipped {$filename}: not found");

                continue;
            }

            $before = filesize($source);
            $newRelativePath = $optimizer->optimizePath($source, 'images', $dims['maxWidth'], $dims['maxHeight'], 80);

            // optimizePath() writes under storage/app/public via the "public"
            // disk (UUID filename) — move it to a stable, predictable name
            // next to the original so the CSS references I already updated
            // ("*.webp" instead of "*.jpg") resolve correctly.
            $generated = storage_path('app/public/'.$newRelativePath);
            $target = $directory.'/'.pathinfo($filename, PATHINFO_FILENAME).'.webp';

            if (! rename($generated, $target)) {
                $this->error("  {$filename}: failed to move optimized file into place, original left untouched");

                continue;
            }

            $after = filesize($target);
            unlink($source);

            $this->line(sprintf(
                '%s -> %s.webp  (%s KB -> %s KB)',
                $filename,
                pathinfo($filename, PATHINFO_FILENAME),
                number_format($before / 1024),
                number_format($after / 1024)
            ));
        }

        foreach (self::RECOMPRESS_JPEG as $filename => $dims) {
            $source = $directory.'/'.$filename;

            if (! is_file($source)) {
                $this->warn("Skipped {$filename}: not found");

                continue;
            }

            $before = filesize($source);

            $image = (new ImageManager(new Driver()))->read($source);
            $image->scaleDown(width: $dims['maxWidth'], height: $dims['maxHeight']);
            file_put_contents($source, (string) $image->toJpeg(quality: 80));

            $after = filesize($source);

            $this->line(sprintf(
                '%s recompressed in place (%s KB -> %s KB)',
                $filename,
                number_format($before / 1024),
                number_format($after / 1024)
            ));
        }

        return self::SUCCESS;
    }
}
