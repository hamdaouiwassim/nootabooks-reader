<?php

/**
 * Shared resize logic for the cover-optimizer tools (CLI script + browser
 * UI) — kept in one place so both stay in lockstep with the app's own sizes
 * (App\Services\Image\ImageOptimizer / Admin\BookController).
 */

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

const COVER_VARIANTS = [
    'lg' => [800, 1200],
    'md' => [600, 900],
    'sm' => [300, 450],
];

const COVER_VARIANT_LABELS = [
    'lg' => 'الغلاف الكبير',
    'md' => 'الغلاف المتوسط',
    'sm' => 'الغلاف الصغير',
];

const COVER_QUALITY = 85;
const COVER_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

/**
 * @return array<string, array{path: string, width: int, height: int}>
 */
function generateCoverVariants(string $sourcePath, string $baseName, string $outputDir): array
{
    $manager = new ImageManager(new Driver());
    $generated = [];

    foreach (COVER_VARIANTS as $suffix => [$maxWidth, $maxHeight]) {
        $image = $manager->read($sourcePath);
        $image->scaleDown(width: $maxWidth, height: $maxHeight);

        $outputPath = rtrim($outputDir, '/\\').DIRECTORY_SEPARATOR."{$baseName}-{$suffix}.webp";
        file_put_contents($outputPath, (string) $image->toWebp(quality: COVER_QUALITY));

        $generated[$suffix] = [
            'path' => $outputPath,
            'width' => $image->width(),
            'height' => $image->height(),
        ];
    }

    return $generated;
}
