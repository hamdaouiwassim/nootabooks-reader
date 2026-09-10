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

    protected $description = 'Re-encode already-uploaded book covers and writer photos as compressed WebP, generating a small card-thumbnail variant alongside each';

    public function handle(ImageOptimizer $optimizer): int
    {
        $this->optimizeColumn(Book::query(), 'cover_image', 'covers', ['' => [800, 1200], '-sm' => [300, 450]], $optimizer);
        $this->optimizeColumn(Writer::query(), 'photo', 'writers', ['' => [600, 600], '-sm' => [300, 300]], $optimizer);

        return self::SUCCESS;
    }

    private function optimizeColumn(Builder $query, string $column, string $directory, array $variants, ImageOptimizer $optimizer): void
    {
        $rows = $query->whereNotNull($column)->get();

        $this->info("Checking {$rows->count()} {$column} value(s)...");

        foreach ($rows as $row) {
            $relativePath = $this->relativeStoragePath($row->{$column});

            if (! $relativePath) {
                continue; // external URL we can't reach locally
            }

            $smallPath = preg_replace('/(\.\w+)$/', '-sm$1', $relativePath);

            if (str_ends_with($relativePath, '.webp') && Storage::disk('public')->exists($smallPath)) {
                continue; // already WebP with a small variant generated
            }

            if (! Storage::disk('public')->exists($relativePath)) {
                $this->warn("  #{$row->id}: file missing on disk, skipped");

                continue;
            }

            $paths = $optimizer->optimizeResponsivePath(
                Storage::disk('public')->path($relativePath),
                $directory,
                $variants,
                85,
            );

            Storage::disk('public')->delete($relativePath);
            Storage::disk('public')->delete($smallPath); // harmless if it never existed

            /** @var Model $row */
            $row->{$column} = str_replace('http://', 'https://', rtrim(config('app.url'), '/')).'/storage/'.$paths[''];
            $row->save();

            $this->line("  #{$row->id}: {$relativePath} -> {$paths['']} (+ small variant)");
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
