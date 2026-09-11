<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Writer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class FixStoredDomain extends Command
{
    protected $signature = 'urls:fix-domain {old : The domain to replace, e.g. nootabooks.nootapedia.com} {new : The domain to replace it with, e.g. nootabooks.com}';

    protected $description = 'Rewrites the domain baked into already-stored cover/photo/file URLs (books.cover_image, books.file_path, writers.photo) after a domain change — changing APP_URL alone only affects new uploads';

    public function handle(): int
    {
        $old = rtrim((string) $this->argument('old'), '/');
        $new = rtrim((string) $this->argument('new'), '/');

        if ($old === '' || $new === '' || $old === $new) {
            $this->error('Provide two different, non-empty domains.');

            return self::FAILURE;
        }

        $this->rewriteColumn(Book::query(), 'cover_image', $old, $new);
        $this->rewriteColumn(Book::query(), 'file_path', $old, $new);
        $this->rewriteColumn(Writer::query(), 'photo', $old, $new);

        return self::SUCCESS;
    }

    private function rewriteColumn(Builder $query, string $column, string $old, string $new): void
    {
        $rows = $query->where($column, 'like', "%{$old}%")->get();

        $this->info("Rewriting {$rows->count()} {$query->getModel()->getTable()}.{$column} value(s)...");

        foreach ($rows as $row) {
            $row->{$column} = str_replace($old, $new, $row->{$column});
            $row->save(); // triggers FlushesAppCache so the home/sitemap cache picks up the new URLs immediately
        }
    }
}
