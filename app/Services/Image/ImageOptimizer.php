<?php

namespace App\Services\Image;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    /**
     * Resize an uploaded image down to fit within the given dimensions
     * (never upscales), convert it to WebP, and store it on the public
     * disk. Returns the disk-relative path. Intervention Image applies
     * EXIF orientation automatically when reading the source file.
     */
    public function optimize(UploadedFile $file, string $directory, int $maxWidth, int $maxHeight, int $quality = 82): string
    {
        return $this->optimizePath($file->getRealPath(), $directory, $maxWidth, $maxHeight, $quality);
    }

    /**
     * Same pipeline as optimize(), but for a file that's already on disk
     * (e.g. an already-uploaded cover/photo being retroactively optimized)
     * rather than a fresh HTTP upload.
     */
    public function optimizePath(string $sourcePath, string $directory, int $maxWidth, int $maxHeight, int $quality = 82): string
    {
        $image = (new ImageManager(new Driver()))->read($sourcePath);

        $image->scaleDown(width: $maxWidth, height: $maxHeight);

        $path = trim($directory, '/').'/'.Str::uuid().'.webp';

        Storage::disk('public')->put($path, (string) $image->toWebp(quality: $quality));

        return $path;
    }
}
