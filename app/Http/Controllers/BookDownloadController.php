<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BookDownloadController extends Controller
{
    public function download(Book $book): Response
    {
        abort_unless($book->file_path, 404);

        $book->increment('downloads_count');

        $relativePath = $this->relativeStoragePath($book->file_path);

        if ($relativePath && Storage::disk('public')->exists($relativePath)) {
            $extension = pathinfo($relativePath, PATHINFO_EXTENSION) ?: 'pdf';
            $filename = trim(preg_replace('/[\\\\\/:*?"<>|]+/', '', $book->title)).'.'.$extension;

            return Storage::disk('public')->download($relativePath, $filename);
        }

        // Not on local storage (e.g. a legacy external URL) — best effort.
        return redirect()->away($book->file_url);
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
