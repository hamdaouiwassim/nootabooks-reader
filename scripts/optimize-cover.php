<?php

/**
 * Standalone local tool — generates the same 3 cover sizes the admin panel's
 * 3 cover-upload fields expect (large/medium/small), from one source image,
 * using the exact same resize bounds + WebP quality as the app's own
 * pipeline (App\Services\Image\ImageOptimizer / Admin\BookController).
 *
 * Not part of the Laravel app itself — run it locally before uploading, via
 * the "الغلاف الكبير/المتوسط/الصغير" fields on the book create/edit form.
 * Just needs `composer install` already run in this project (reuses its
 * vendor/autoload.php for intervention/image) — no separate setup needed.
 *
 * Prefer a file picker over typing paths? Run `scripts/optimize-cover-server.php`
 * instead (see COMMANDS.md) — same logic, browser upload UI.
 *
 * Usage:
 *   php scripts/optimize-cover.php <source-image-or-directory> [output-directory]
 *
 * Examples:
 *   php scripts/optimize-cover.php ~/covers/blue-elephant.jpg
 *     -> writes blue-elephant-lg.webp / -md.webp / -sm.webp next to the
 *        source, in a new "optimized" subfolder.
 *
 *   php scripts/optimize-cover.php ~/covers ~/covers/ready
 *     -> batch mode: processes every .jpg/.jpeg/.png/.webp file directly
 *        inside ~/covers, writing all output into ~/covers/ready.
 */

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/lib/cover-variants.php';

function usageAndExit(): never
{
    fwrite(STDERR, "Usage: php scripts/optimize-cover.php <source-image-or-directory> [output-directory]\n");
    exit(1);
}

function processFile(string $sourcePath, string $outputDir): void
{
    $baseName = pathinfo($sourcePath, PATHINFO_FILENAME);

    foreach (generateCoverVariants($sourcePath, $baseName, $outputDir) as $suffix => $info) {
        [$maxWidth, $maxHeight] = COVER_VARIANTS[$suffix];
        echo "  ✓ {$info['path']} (max {$maxWidth}×{$maxHeight}, actual {$info['width']}×{$info['height']})\n";
    }
}

$source = $argv[1] ?? null;

if (! $source || ! file_exists($source)) {
    usageAndExit();
}

$isDirectory = is_dir($source);
$outputDir = $argv[2] ?? ($isDirectory ? rtrim($source, '/\\').DIRECTORY_SEPARATOR.'optimized' : dirname($source).DIRECTORY_SEPARATOR.'optimized');

if (! is_dir($outputDir) && ! mkdir($outputDir, 0755, true) && ! is_dir($outputDir)) {
    fwrite(STDERR, "Could not create output directory: {$outputDir}\n");
    exit(1);
}

if ($isDirectory) {
    $files = array_filter(scandir($source), function ($name) use ($source) {
        if (is_dir($source.DIRECTORY_SEPARATOR.$name)) {
            return false;
        }

        return in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), COVER_EXTENSIONS, true);
    });

    if (empty($files)) {
        fwrite(STDERR, 'No image files ('.implode('/', COVER_EXTENSIONS).") found in {$source}\n");
        exit(1);
    }

    echo 'Found '.count($files)." image(s) in {$source}\n\n";

    foreach ($files as $file) {
        echo "{$file}:\n";
        processFile($source.DIRECTORY_SEPARATOR.$file, $outputDir);
        echo "\n";
    }
} else {
    processFile($source, $outputDir);
}

echo "Done. Output in: {$outputDir}\n";
