<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Services\Image\CoverRibbonStamper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class StampCoverRibbon extends Command
{
    protected $signature = 'covers:stamp-ribbon {--force : Re-stamp covers even if already marked as stamped}';

    protected $description = 'Bake the nootabooks.com brand ribbon into every uploaded book cover that does not have it yet (see CoverRibbonStamper) — one-time backfill for covers uploaded before the ribbon was baked in automatically';

    public function handle(CoverRibbonStamper $stamper): int
    {
        $books = Book::whereNotNull('cover_image')
            ->when(! $this->option('force'), fn ($query) => $query->where('cover_ribbon_stamped', false))
            ->get();

        $this->info("Checking {$books->count()} book cover(s)...");

        $stamped = 0;
        $skipped = 0;

        foreach ($books as $book) {
            $relativePath = $this->relativeStoragePath($book->cover_image);

            if (! $relativePath) {
                $this->warn("  Book #{$book->id}: external cover URL, can't stamp locally, skipped");
                $skipped++;

                continue;
            }

            if (! Storage::disk('public')->exists($relativePath)) {
                $this->warn("  Book #{$book->id}: cover file missing on disk, skipped");
                $skipped++;

                continue;
            }

            try {
                $newRelativePath = $stamper->stampPath(
                    Storage::disk('public')->path($relativePath),
                    'covers',
                    300,
                    450,
                    80,
                );
            } catch (\Throwable $e) {
                $this->error("  Book #{$book->id}: failed to stamp ({$e->getMessage()})");
                $skipped++;

                continue;
            }

            Storage::disk('public')->delete($relativePath);
            $book->cover_image = force_https_url(rtrim(config('app.url'), '/')).'/storage/'.$newRelativePath;
            $book->cover_ribbon_stamped = true;
            $book->save();

            $stamped++;
            $this->line("  Book #{$book->id}: ribbon stamped");
        }

        $this->info("Done: {$stamped} stamped, {$skipped} skipped.");

        return self::SUCCESS;
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
