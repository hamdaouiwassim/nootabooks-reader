<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\Admin\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use App\Models\DownloadLog;
use App\Models\Series;
use App\Models\Writer;
use App\Services\Image\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Smalot\PdfParser\Parser as PdfParser;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $books = Book::query()
            ->with(['category', 'writer'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q');
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhereHas('writer', fn ($w) => $w->where('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->whereHas('categories', fn ($cq) => $cq->where('categories.id', $request->integer('category'))))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->tap(function ($query) use ($request) {
                match ($request->string('sort', 'newest')->toString()) {
                    'oldest' => $query->oldest(),
                    'title_asc' => $query->orderBy('title'),
                    'title_desc' => $query->orderByDesc('title'),
                    'downloads_desc' => $query->orderByDesc('downloads_count'),
                    'rating_desc' => $query->orderByDesc('rating_average'),
                    'published_year_desc' => $query->orderByDesc('published_year'),
                    default => $query->latest(),
                };
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.books.index', [
            'activeNav' => 'books',
            'books' => $books,
            'categories' => Category::orderBy('name')->get(),
            'totalBooks' => Book::count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.books.create', [
            'activeNav' => 'books',
            'categories' => Category::orderBy('name')->get(),
            'writers' => Writer::orderBy('name')->get(),
            'selectedCategoryIds' => [],
            'allSeries' => Series::orderBy('name')->get(),
        ]);
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);

        $book = Book::create($data);
        $book->categories()->sync($this->resolveCategoryIds($request, $data['category_id']));
        $this->syncFaqs($book, $request);

        return redirect()->route('admin.books.edit', $book)->with('status', 'تم حفظ الكتاب بنجاح');
    }

    public function show(Book $book): View
    {
        $book->load(['writer', 'category', 'categories', 'series']);

        return view('admin.books.show', [
            'activeNav' => 'books',
            'book' => $book,
            'recentDownloads' => DownloadLog::where('book_id', $book->id)->with('user')->latest()->limit(5)->get(),
        ]);
    }

    public function edit(Book $book): View
    {
        return view('admin.books.edit', [
            'activeNav' => 'books',
            'book' => $book,
            'categories' => Category::orderBy('name')->get(),
            'writers' => Writer::orderBy('name')->get(),
            'selectedCategoryIds' => $book->categories->pluck('id')->all(),
            'allSeries' => Series::orderBy('name')->get(),
        ]);
    }

    public function stats(Request $request, Book $book): View
    {
        $period = $request->get('period', '7d');

        if (! in_array($period, ['day', '7d', '1m', '3m', 'custom'], true)) {
            $period = '7d';
        }

        $fromInput = $request->get('from');
        $toInput = $request->get('to');

        if ($period === 'day') {
            $dailyDownloads = DownloadLog::perHour($book->id);
            $periodLabel = 'اليوم، '.now()->translatedFormat('j F Y');
        } elseif ($period === 'custom') {
            $to = $toInput ? Carbon::parse($toInput)->endOfDay() : now();
            $from = $fromInput ? Carbon::parse($fromInput)->startOfDay() : $to->copy()->subDays(6)->startOfDay();

            if ($from->gt($to)) {
                [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
            }

            if ($from->diffInDays($to) > 366) {
                $from = $to->copy()->subDays(366)->startOfDay();
            }

            $dailyDownloads = DownloadLog::perDayRange($from, $to, $book->id);
            $periodLabel = 'من '.$from->translatedFormat('j M Y').' إلى '.$to->translatedFormat('j M Y');
            $fromInput = $from->format('Y-m-d');
            $toInput = $to->format('Y-m-d');
        } else {
            $days = match ($period) {
                '7d' => 7,
                '3m' => 90,
                default => 30, // '1m'
            };
            $dailyDownloads = DownloadLog::perDay($days, $book->id);
            $periodLabel = match ($period) {
                '7d' => 'آخر 7 أيام',
                '3m' => 'آخر 3 أشهر',
                default => 'الشهر الماضي',
            };
        }

        return view('admin.books.stats', [
            'activeNav' => 'books',
            'book' => $book,
            'dailyDownloads' => $dailyDownloads,
            'period' => $period,
            'periodLabel' => $periodLabel,
            'fromInput' => $fromInput ?? now()->subDays(6)->format('Y-m-d'),
            'toInput' => $toInput ?? now()->format('Y-m-d'),
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $this->prepareData($request, $book);

        $book->update($data);
        $book->categories()->sync($this->resolveCategoryIds($request, $data['category_id']));
        $this->syncFaqs($book, $request);

        return redirect()->route('admin.books.edit', $book)->with('status', 'تم حفظ التعديلات بنجاح');
    }

    /**
     * Wholesale-replaces the book's FAQs from the submitted repeater rows —
     * simplest correct approach given a book only ever has a handful of
     * FAQs. Rows with no question/answer (e.g. an empty trailing row left
     * in the form) are skipped. sort_order is just the row's position in
     * the submitted array, which PHP preserves in submission order.
     */
    private function syncFaqs(Book $book, Request $request): void
    {
        $book->faqs()->delete();

        $order = 0;

        foreach ($request->input('faqs', []) as $row) {
            $question = trim((string) ($row['question'] ?? ''));
            $answer = trim((string) ($row['answer'] ?? ''));

            if ($question === '' || $answer === '') {
                continue;
            }

            $book->faqs()->create([
                'question' => $question,
                'answer' => $answer,
                'sort_order' => $order++,
                'is_active' => ! empty($row['is_active']),
            ]);
        }
    }

    /**
     * The primary category (category_id) is always included in the pivot
     * alongside whatever extra categories the admin checked, so
     * Category::books() sees every book regardless of which category is
     * primary — no book can end up "missing" from its own primary category.
     */
    private function resolveCategoryIds(Request $request, int $primaryCategoryId): array
    {
        $extra = array_map('intval', $request->input('categories', []));

        return array_values(array_unique([$primaryCategoryId, ...$extra]));
    }

    public function toggleCopyrightBlock(Book $book): RedirectResponse
    {
        $book->update(['copyright_blocked' => ! $book->copyright_blocked]);

        $status = $book->copyright_blocked
            ? 'تم حظر الكتاب بسبب حقوق النشر — لن يظهر زرا القراءة والتحميل بعد الآن'
            : 'تم إلغاء حظر الكتاب';

        return back()->with('status', $status);
    }

    public function destroy(Book $book): RedirectResponse
    {
        foreach ([$book->cover_image, $book->cover_image_md, $book->cover_image_sm] as $coverValue) {
            if ($relativePath = $this->relativeStoragePath($coverValue)) {
                Storage::disk('public')->delete($relativePath);
            }
        }

        if ($relativePath = $this->relativeStoragePath($book->file_path)) {
            Storage::disk('public')->delete($relativePath);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('status', 'تم حذف الكتاب بنجاح');
    }

    private function prepareData(Request $request, ?Book $book = null): array
    {
        $data = $request->validated();

        $data['is_coming_soon'] = $request->boolean('is_coming_soon');
        $data['download_disabled'] = $request->boolean('download_disabled');
        $data['reading_disabled'] = $request->boolean('reading_disabled');
        $data['copyright_blocked'] = $request->boolean('copyright_blocked');
        unset($data['categories']); // extra categories go through the book_category pivot, not a books column

        $seriesName = trim((string) ($data['series_name'] ?? ''));
        unset($data['series_name']); // resolved into series_id below, not a books column itself

        if ($seriesName !== '') {
            $data['series_id'] = Series::firstOrCreate(['name' => $seriesName])->id;
        } else {
            $data['series_id'] = null;
            $data['series_order'] = null;
        }

        $data['tags'] = $request->filled('tags')
            ? array_values(array_filter(array_map('trim', preg_split('/[,،]/u', (string) $request->string('tags')))))
            : [];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->storeCoverVariant($request->file('cover_image'), $book?->cover_image);
        } else {
            unset($data['cover_image']);
        }

        if ($request->hasFile('book_file')) {
            if ($relativePath = $this->relativeStoragePath($book?->file_path)) {
                Storage::disk('public')->delete($relativePath);
            }

            $bookFile = $request->file('book_file');
            $this->detectFileMetadata($bookFile, $data);

            $path = $bookFile->store('books', 'public');
            $data['file_path'] = force_https_url(rtrim(config('app.url'), '/')).'/storage/'.$path;
        }

        unset($data['book_file']);

        return $data;
    }

    /**
     * Deletes the old cover (if any), compresses the new upload to WebP at
     * the single size used everywhere on the site (300×450 — see
     * Book::cover_image_{md,sm}_url, which fall back to this same file), and
     * returns the full URL to store.
     */
    private function storeCoverVariant(UploadedFile $file, ?string $oldValue): string
    {
        if ($relativePath = $this->relativeStoragePath($oldValue)) {
            Storage::disk('public')->delete($relativePath);
        }

        $path = app(ImageOptimizer::class)->optimize($file, 'covers', 300, 450, 80);

        return force_https_url(rtrim(config('app.url'), '/')).'/storage/'.$path;
    }

    /**
     * Auto-fill file_size_mb and pages_count — read straight from the
     * uploaded PDF rather than trusting manual admin input, which overrides
     * whatever was typed in those two form fields.
     */
    private function detectFileMetadata(UploadedFile $file, array &$data): void
    {
        $data['file_size_mb'] = round($file->getSize() / 1024 / 1024, 2);

        try {
            $pdf = (new PdfParser())->parseFile($file->getRealPath());
            $pageCount = count($pdf->getPages());

            if ($pageCount > 0) {
                $data['pages_count'] = $pageCount;

                return;
            }
        } catch (\Throwable $e) {
            // smalot/pdfparser chokes on some PDFs whose /Kids tree isn't a
            // plain array (see smalot/pdfparser#331) — fall back to reading
            // the page count straight off the raw PDF bytes below.
            logger()->warning('PDF page count detection failed, trying raw fallback', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);
        }

        if ($pageCount = $this->countPdfPagesFromRawBytes($file->getRealPath())) {
            $data['pages_count'] = $pageCount;
        }
    }

    /**
     * Last-resort page count for PDFs that smalot/pdfparser can't walk:
     * scan each indirect object for a /Type /Pages dictionary and read its
     * /Count directly, falling back to counting bare /Type /Page objects.
     * Won't find pages hidden inside compressed object streams, but covers
     * the common case where only the page tree itself is malformed.
     */
    private function countPdfPagesFromRawBytes(string $path): ?int
    {
        $raw = file_get_contents($path);

        if ($raw === false) {
            return null;
        }

        if (preg_match_all('/\d+\s+\d+\s+obj(.*?)endobj/s', $raw, $objectMatches)) {
            $counts = [];

            foreach ($objectMatches[1] as $objectBody) {
                if (preg_match('/\/Type\s*\/Pages\b/', $objectBody)
                    && preg_match('/\/Count\s+(\d+)/', $objectBody, $countMatch)) {
                    $counts[] = (int) $countMatch[1];
                }
            }

            if ($counts) {
                return max($counts);
            }
        }

        if (preg_match_all('/\/Type\s*\/Page(?!s)\b/', $raw, $pageMatches)) {
            return count($pageMatches[0]) ?: null;
        }

        return null;
    }

    /**
     * Extract the disk-relative path from a stored file value, whether it's a
     * full URL (current format) or a bare "storage/..." path (legacy rows
     * saved before files were stored as absolute URLs).
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
