<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\DownloadLog;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BookDownloadController extends Controller
{
    public function download(Book $book): Response
    {
        abort_if($book->is_coming_soon, 404);
        abort_if($book->download_disabled, 404);
        abort_unless($book->status === 'published', 404);
        abort_unless($book->file_path, 404);

        $relativePath = $this->relativeStoragePath($book->file_path);

        if ($relativePath && Storage::disk('public')->exists($relativePath)) {
            $extension = pathinfo($relativePath, PATHINFO_EXTENSION) ?: 'pdf';
            $title = trim(preg_replace('/[\\\\\/:*?"<>|]+/', '', $book->title));
            $filename = "{$title}-(nootabooks.com).{$extension}";

            $book->increment('downloads_count');
            $this->logDownload($book);

            return Storage::disk('public')->download($relativePath, $filename);
        }

        if (! $relativePath) {
            // A genuine legacy external URL (never lived on our disk) — best
            // effort, so still count it: we're handing the visitor a real
            // place to get the file, even though we can't confirm it loads.
            $book->increment('downloads_count');
            $this->logDownload($book);

            return redirect()->away($book->file_url);
        }

        // $relativePath resolved (this was supposed to be a local file) but
        // it's missing from disk — don't count a download that can't happen.
        abort(404);
    }

    /**
     * Streams the book file inline (for the in-app reader iframe) instead of
     * exposing the permanent /storage/... URL directly to the client.
     */
    public function stream(Book $book): Response
    {
        abort_if($book->is_coming_soon, 404);
        abort_unless($book->status === 'published', 404);
        abort_unless($book->file_path, 404);

        $relativePath = $this->relativeStoragePath($book->file_path);

        abort_unless($relativePath && Storage::disk('public')->exists($relativePath), 404);

        return Storage::disk('public')->response($relativePath);
    }

    /**
     * Records a timestamped download event (in addition to the running
     * Book::downloads_count counter) so admin stats can chart downloads per
     * day — the counter alone has no history to bucket by date.
     */
    private function logDownload(Book $book): void
    {
        DownloadLog::create([
            'book_id' => $book->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Extract the disk-relative path from a stored file value, whether it's a
     * full URL (current format) or a bare "storage/..." path (legacy rows).
     */
    private function relativeStoragePath(?string $storedValue): ?string
    {
        if (! $storedValue) {
            return null;
        }

        if (str_starts_with($storedValue, 'storage/')) {
            return substr($storedValue, strlen('storage/'));
        }

        $marker = '/storage/';
        $position = strpos($storedValue, $marker);

        return $position !== false ? substr($storedValue, $position + strlen($marker)) : null;
    }
}
