<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Writer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function autocomplete(Request $request): JsonResponse
    {
        $search = trim((string) $request->input('q', ''));

        if (mb_strlen($search) < 2) {
            return response()->json([]);
        }

        // Title-only (not also matching writer name like the full /discover
        // search) — writer matches already get their own 'author' row below,
        // so matching them here too would flood the list with one popular
        // writer's books instead of a clean, separate author suggestion.
        $books = Book::published()->with('writer')
            ->where('title', 'like', "%{$search}%")
            ->orderByDesc('downloads_count')
            ->take(5)
            ->get()
            ->map(fn (Book $book) => [
                'type' => 'book',
                'title' => $book->title,
                'subtitle' => $book->writer?->name,
                'thumbnail' => $book->cover_image_sm_url,
                'url' => route('book-details', $book->slug),
            ]);

        $writers = Writer::indexable()
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%");
            })
            ->orderByDesc('followers_count')
            ->take(3)
            ->get()
            ->map(fn (Writer $writer) => [
                'type' => 'author',
                'title' => $writer->name,
                'subtitle' => null,
                'thumbnail' => $writer->photo_xs_url,
                'url' => route('writer-details', $writer->slug),
            ]);

        return response()->json($books->concat($writers)->values());
    }
}
