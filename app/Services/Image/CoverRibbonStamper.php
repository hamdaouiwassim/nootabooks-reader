<?php

namespace App\Services\Image;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Bakes the "nootabooks.com" brand ribbon directly into a book cover's
 * pixels, so it survives the raw file being saved/hotlinked/shared outside
 * the site — unlike the CSS .brand-ribbon overlay still used for placeholder
 * covers (books with no uploaded photo), which has no underlying image to
 * composite onto.
 *
 * Deliberately kept separate from ImageOptimizer, which is shared with
 * writer photos (see Admin\WriterController, OptimizeExistingImages) —
 * those must never receive a ribbon, so the stamping logic lives entirely
 * here instead of behind a flag/closure on the generic optimizer.
 */
class CoverRibbonStamper
{
    private const RIBBON_ASSET = 'assets/images/cover-ribbon-overlay.png';

    public function stamp(UploadedFile $file, string $directory, int $maxWidth, int $maxHeight, int $quality = 80): string
    {
        return $this->stampPath($file->getRealPath(), $directory, $maxWidth, $maxHeight, $quality);
    }

    public function stampPath(string $sourcePath, string $directory, int $maxWidth, int $maxHeight, int $quality = 80): string
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($sourcePath);
        $image->scaleDown(width: $maxWidth, height: $maxHeight);

        // The ribbon asset is authored for the standard 300x450 cover, but
        // scaleDown() never upscales and only fits within bounds — a
        // non-2:3 or undersized source won't land on exactly 300x450, so
        // match the ribbon to the base's actual post-scale dimensions
        // before compositing.
        $ribbon = $manager->read(public_path(self::RIBBON_ASSET));

        if ($ribbon->width() !== $image->width() || $ribbon->height() !== $image->height()) {
            $ribbon->resize($image->width(), $image->height());
        }

        $image->place($ribbon, 'top-left', 0, 0);

        $path = trim($directory, '/').'/'.Str::uuid().'.webp';

        Storage::disk('public')->put($path, (string) $image->toWebp(quality: $quality));

        return $path;
    }
}
