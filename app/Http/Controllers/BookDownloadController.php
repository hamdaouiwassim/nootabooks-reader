<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BookDownloadController extends Controller
{
    public function download(Book $book): Response
    {
        abort_if($book->is_coming_soon, 404);
        abort_unless($book->status === 'published', 404);
        abort_unless($book->file_path, 404);

        $book->increment('downloads_count');

        $relativePath = $this->relativeStoragePath($book->file_path);

        if ($relativePath && Storage::disk('public')->exists($relativePath)) {
            $extension = pathinfo($relativePath, PATHINFO_EXTENSION) ?: 'pdf';
            $title = trim(preg_replace('/[\\\\\/:*?"<>|]+/', '', $book->title));
            $filename = "{$title}-(nootabooks.com).{$extension}";

            return Storage::disk('public')->download($relativePath, $filename);
        }

        // Not on local storage (e.g. a legacy external URL) — best effort.
        return redirect()->away($book->file_url);
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
