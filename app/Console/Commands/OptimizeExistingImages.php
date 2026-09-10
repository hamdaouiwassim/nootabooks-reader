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

    protected $description = 'Re-encode already-uploaded book covers and writer photos as compressed WebP';

    public function handle(ImageOptimizer $optimizer): int
    {
        $this->optimizeColumn(Book::query(), 'cover_image', 'covers', 800, 1200, $optimizer);
        $this->optimizeColumn(Writer::query(), 'photo', 'writers', 600, 600, $optimizer);

        return self::SUCCESS;
    }

    private function optimizeColumn(Builder $query, string $column, string $directory, int $maxWidth, int $maxHeight, ImageOptimizer $optimizer): void
    {
        $rows = $query->whereNotNull($column)->get();

        $this->info("Checking {$rows->count()} {$column} value(s)...");

        foreach ($rows as $row) {
            $relativePath = $this->relativeStoragePath($row->{$column});

            if (! $relativePath || str_ends_with($relativePath, '.webp')) {
                continue; // already WebP, or an external URL we can't reach locally
            }

            if (! Storage::disk('public')->exists($relativePath)) {
                $this->warn("  #{$row->id}: file missing on disk, skipped");

                continue;
            }

            $newPath = $optimizer->optimizePath(
                Storage::disk('public')->path($relativePath),
                $directory,
                $maxWidth,
                $maxHeight,
                85,
            );

            Storage::disk('public')->delete($relativePath);

            /** @var Model $row */
            $row->{$column} = str_replace('http://', 'https://', rtrim(config('app.url'), '/')).'/storage/'.$newPath;
            $row->save();

            $this->line("  #{$row->id}: {$relativePath} -> {$newPath}");
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
