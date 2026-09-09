<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\Admin\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use App\Models\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->latest()
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
        ]);
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);

        $book = Book::create($data);

        return redirect()->route('admin.books.edit', $book)->with('status', 'تم حفظ الكتاب بنجاح');
    }

    public function edit(Book $book): View
    {
        return view('admin.books.edit', [
            'activeNav' => 'books',
            'book' => $book,
            'categories' => Category::orderBy('name')->get(),
            'writers' => Writer::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $this->prepareData($request, $book);

        $book->update($data);

        return redirect()->route('admin.books.edit', $book)->with('status', 'تم حفظ التعديلات بنجاح');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($relativePath = $this->relativeStoragePath($book->cover_image)) {
            Storage::disk('public')->delete($relativePath);
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

        $data['formats'] = $request->input('formats', []);
        $data['tags'] = $request->filled('tags')
            ? array_values(array_filter(array_map('trim', preg_split('/[,،]/u', (string) $request->string('tags')))))
            : [];

        if ($request->hasFile('cover_image')) {
            if ($relativePath = $this->relativeStoragePath($book?->cover_image)) {
                Storage::disk('public')->delete($relativePath);
            }

            $path = $request->file('cover_image')->store('covers', 'public');
            $data['cover_image'] = rtrim(config('app.url'), '/').'/storage/'.$path;
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
            $data['file_path'] = rtrim(config('app.url'), '/').'/storage/'.$path;
        }

        unset($data['book_file']);

        return $data;
    }

    /**
     * Auto-fill file_size_mb (any format) and, for PDFs, pages_count — read
     * straight from the uploaded file rather than trusting manual admin
     * input, which overrides whatever was typed in those two form fields.
     */
    private function detectFileMetadata(UploadedFile $file, array &$data): void
    {
        $data['file_size_mb'] = round($file->getSize() / 1024 / 1024, 2);

        if (strtolower($file->getClientOriginalExtension()) !== 'pdf') {
            return;
        }

        try {
            $pdf = (new PdfParser())->parseFile($file->getRealPath());
            $data['pages_count'] = count($pdf->getPages());
        } catch (\Throwable) {
            // Corrupted/encrypted/unreadable PDF — keep the manually entered value, if any.
        }
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
