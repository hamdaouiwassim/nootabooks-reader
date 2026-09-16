<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FileAuditController extends Controller
{
    private const PDF_WARN_MB = 50;

    private const COVER_WARN_KB = 200;

    /**
     * Scans every book's PDF (size already stored in file_size_mb at upload
     * time — see Admin\BookController::detectFileMetadata()) and cover image
     * (stat'd fresh from disk here, since no size column is kept for it) and
     * flags anything unusually large, so storage bloat/legacy oversized
     * uploads are easy to spot instead of silently accumulating.
     */
    public function index(Request $request): View
    {
        $books = Book::query()
            ->where(fn ($q) => $q->whereNotNull('file_path')->orWhereNotNull('cover_image'))
            ->get(['id', 'title', 'slug', 'cover_image', 'file_path', 'file_size_mb']);

        $rows = $books->map(function (Book $book) {
            $coverSizeKb = $this->localFileSizeKb($book->cover_image);
            $pdfSizeMb = $book->file_size_mb !== null ? (float) $book->file_size_mb : null;

            return (object) [
                'book' => $book,
                'pdfSizeMb' => $pdfSizeMb,
                'pdfIsExternal' => (bool) $book->file_path && ! $this->relativeStoragePath($book->file_path),
                'pdfOversized' => $pdfSizeMb !== null && $pdfSizeMb > self::PDF_WARN_MB,
                'coverSizeKb' => $coverSizeKb,
                'coverIsExternal' => (bool) $book->cover_image && ! $this->relativeStoragePath($book->cover_image),
                'coverOversized' => $coverSizeKb !== null && $coverSizeKb > self::COVER_WARN_KB,
            ];
        })->sortByDesc(fn ($row) => $row->pdfSizeMb ?? 0)->values();

        $summary = [
            'totalBooks' => $rows->count(),
            'totalPdfMb' => round((float) $rows->sum('pdfSizeMb'), 1),
            'totalCoverMb' => round($rows->sum('coverSizeKb') / 1024, 2),
            'oversizedPdfCount' => $rows->where('pdfOversized', true)->count(),
            'oversizedCoverCount' => $rows->where('coverOversized', true)->count(),
        ];

        $perPage = 30;
        $page = $request->integer('page', 1);
        $paginatedRows = new LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.file-audit', [
            'activeNav' => 'file-audit',
            'rows' => $paginatedRows,
            'summary' => $summary,
            'pdfWarnMb' => self::PDF_WARN_MB,
            'coverWarnKb' => self::COVER_WARN_KB,
        ]);
    }

    private function localFileSizeKb(?string $storedValue): ?float
    {
        $relativePath = $this->relativeStoragePath($storedValue);

        if (! $relativePath || ! Storage::disk('public')->exists($relativePath)) {
            return null;
        }

        return round(Storage::disk('public')->size($relativePath) / 1024, 1);
    }

    /**
     * Extract the disk-relative path from a stored file value, whether it's a
     * full URL (current format) or a bare "storage/..." path (legacy rows) —
     * returns null for a genuine external URL, which was never on our disk.
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
