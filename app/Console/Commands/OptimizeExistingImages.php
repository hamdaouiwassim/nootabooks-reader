<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Writer;
use App\Services\Image\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImages extends Command
{
    protected $signature = 'images:optimize';

    protected $description = 'Re-encode already-uploaded book covers and writer photos as compressed WebP, shrinking book covers to 300×450 and backfilling any missing writer-photo size variant';

    public function handle(ImageOptimizer $optimizer): int
    {
        $this->optimizeBookCovers($optimizer);
        $this->optimizeColumn(Writer::query(), 'photo', 'writers', ['' => [600, 600], '-sm' => [300, 300], '-xs' => [200, 200]], $optimizer);

        return self::SUCCESS;
    }

    /**
     * Books store a single compressed cover (cover_image, capped at 300×450 —
     * see Admin\BookController::storeCoverVariant()); cover_image_md/_sm are
     * legacy columns that Book::cover_image_{md,sm}_url now simply falls back
     * from to this same file. Retroactively: converts a legacy non-WebP cover
     * to WebP and shrinks any cover still larger than 300×450 down to it.
     */
    private function optimizeBookCovers(ImageOptimizer $optimizer): void
    {
        $books = Book::whereNotNull('cover_image')->get();

        $this->info("Checking {$books->count()} book cover_image value(s)...");

        foreach ($books as $book) {
            $relativePath = $this->relativeStoragePath($book->cover_image);

            if (! $relativePath) {
                continue; // external URL we can't reach locally
            }

            if (! Storage::disk('public')->exists($relativePath)) {
                $this->warn("  Book #{$book->id}: cover file missing on disk, skipped");

                continue;
            }

            $needsWebp = ! str_ends_with($relativePath, '.webp');
            $sourcePath = Storage::disk('public')->path($relativePath);
            [$width, $height] = @getimagesize($sourcePath) ?: [0, 0];
            $needsShrink = $width > 300 || $height > 450;

            if (! $needsWebp && ! $needsShrink) {
                continue; // already a compressed 300×450-max webp
            }

            $newRelativePath = $optimizer->optimizePath($sourcePath, 'covers', 300, 450, 80);
            Storage::disk('public')->delete($relativePath);
            $book->cover_image = force_https_url(rtrim(config('app.url'), '/')).'/storage/'.$newRelativePath;
            $book->save();

            $this->line("  Book #{$book->id}: cover compressed to ≤300×450 webp");
        }
    }

    private function optimizeColumn(Builder $query, string $column, string $directory, array $variants, ImageOptimizer $optimizer): void
    {
        $rows = $query->whereNotNull($column)->get();
        $suffixes = array_filter(array_keys($variants), fn ($suffix) => $suffix !== '');

        $this->info("Checking {$rows->count()} {$column} value(s)...");

        foreach ($rows as $row) {
            $relativePath = $this->relativeStoragePath($row->{$column});

            if (! $relativePath) {
                continue; // external URL we can't reach locally
            }

            $variantPaths = collect($suffixes)
                ->mapWithKeys(fn ($suffix) => [$suffix => preg_replace('/(\.\w+)$/', "{$suffix}\$1", $relativePath)]);

            $allVariantsExist = $variantPaths->every(fn ($path) => Storage::disk('public')->exists($path));

            if (str_ends_with($relativePath, '.webp') && $allVariantsExist) {
                continue; // already WebP with every declared variant generated
            }

            if (! Storage::disk('public')->exists($relativePath)) {
                $this->warn("  #{$row->id}: file missing on disk, skipped");

                continue;
            }

            $paths = $optimizer->optimizeResponsivePath(
                Storage::disk('public')->path($relativePath),
                $directory,
                $variants,
                80,
            );

            Storage::disk('public')->delete($relativePath);
            foreach ($variantPaths as $path) {
                Storage::disk('public')->delete($path); // harmless if it never existed
            }

            /** @var Model $row */
            $row->{$column} = force_https_url(rtrim(config('app.url'), '/')).'/storage/'.$paths[''];
            $row->save();

            $this->line("  #{$row->id}: {$relativePath} -> {$paths['']} (+ ".count($suffixes)." variant(s))");
        }
    }

    private function relativeStoragePath(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (str_starts_with($value, 'storage/')) {
            return substr($value, strlen('storage/'));
        }

        $marker = '/storage/';
        $position = strpos($value, $marker);

        return $position !== false ? substr($value, $position + strlen($marker)) : null;
    }
}
