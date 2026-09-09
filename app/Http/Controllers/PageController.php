<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $trendingBooks = Book::with('writer')
            ->orderByDesc('downloads_count')
            ->take(10)
            ->get();

        $similarBooks = Book::with('writer')
            ->whereNotIn('id', $trendingBooks->pluck('id'))
            ->orderByDesc('rating_average')
            ->take(4)
            ->get();

        $categories = Category::orderBy('id')->take(8)->get();

        return view('home', [
            'activeNav' => 'home',
            'trendingBooks' => $trendingBooks,
            'similarBooks' => $similarBooks,
            'categories' => $categories,
            'booksCount' => Book::count(),
            'writersCount' => Writer::count(),
            'categoriesCount' => Category::count(),
        ]);
    }

    public function discover(): View
    {
        return view('discover', [
            'activeNav' => 'discover',
            'books' => Book::with(['category', 'writer'])->paginate(9)->withQueryString(),
        ]);
    }

    public function categories(): View
    {
        return view('categories', [
            'activeNav' => 'categories',
            'categories' => Category::withCount('books')->orderBy('id')->paginate(12)->withQueryString(),
            'topCategories' => Category::withCount('books')->orderByDesc('books_count')->take(5)->get(),
        ]);
    }
    public function myLibrary(): View
    {
        return view('my-library', ['activeNav' => 'my-library']);
    }

    public function writers(): View
    {
        return view('writers', [
            'activeNav' => 'writers',
            'writers' => Writer::withCount('books')->orderBy('name')->paginate(9)->withQueryString(),
            'featuredWriter' => Writer::withCount('books')->where('is_featured', true)->first(),
        ]);
    }

    public function writerDetails(?string $writer = null): View
    {
        $currentWriter = Writer::withCount('books')
            ->where('slug', $writer ?? 'ahmed-mourad')
            ->firstOrFail();

        $writerBooks = $currentWriter->books()
            ->orderByDesc('published_year')
            ->paginate(8)
            ->withQueryString();

        $similarWriters = Writer::where('id', '!=', $currentWriter->id)
            ->orderByDesc('followers_count')
            ->take(4)
            ->get();

        return view('writer-details', [
            'activeNav' => 'writers',
            'currentWriter' => $currentWriter,
            'writerBooks' => $writerBooks,
            'similarWriters' => $similarWriters,
        ]);
    }

    public function bookDetails(?string $book = null): View
    {
        $currentBook = Book::with(['category', 'writer'])
            ->where('slug', $book ?? 'blue-elephant')
            ->firstOrFail();

        $ratingBreakdown = [];
        foreach ([5, 4, 3, 2, 1] as $stars) {
            $count = $currentBook->reviews()->where('rating', $stars)->count();
            $ratingBreakdown[$stars] = $currentBook->rating_count > 0
                ? round($count / $currentBook->rating_count * 100)
                : 0;
        }

        $reviews = $currentBook->reviews()->with('user')->latest()->paginate(5)->withQueryString();

        $similarBooks = Book::with('writer')
            ->where('category_id', $currentBook->category_id)
            ->where('id', '!=', $currentBook->id)
            ->orderByDesc('rating_average')
            ->take(5)
            ->get();

        return view('book-details', [
            'activeNav' => null,
            'currentBook' => $currentBook,
            'ratingBreakdown' => $ratingBreakdown,
            'reviews' => $reviews,
            'similarBooks' => $similarBooks,
        ]);
    }

    public function read(?string $book = null): View
    {
        return view('read', ['bookSlug' => $book ?? 'zikola-land']);
    }

    public function community(): View
    {
        return view('community', ['activeNav' => 'community']);
    }

    public function readingClubs(): View
    {
        return view('reading-clubs', ['activeNav' => 'community']);
    }

    public function clubDetails(?string $club = null): View
    {
        return view('club-details', ['activeNav' => 'community', 'clubSlug' => $club]);
    }

    public function discussionDetails(?string $discussion = null): View
    {
        return view('discussion-details', ['activeNav' => 'community', 'discussionSlug' => $discussion]);
    }

    public function profile(): View
    {
        return view('profile', ['activeNav' => null]);
    }

    public function settings(): View
    {
        return view('settings', ['activeNav' => null]);
    }

    public function notifications(): View
    {
        return view('notifications', ['activeNav' => null]);
    }

    public function contact(): View
    {
        return view('contact', ['activeNav' => null]);
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // NOTE: no mail/notification backend wired up yet — this is a UI-scope
        // conversion. Hook a Mailable or notification here when ready.

        return back()->with('contactSuccess', 'تم إرسال رسالتك بنجاح، سنتواصل معك قريبًا');
    }

    public function privacy(): View
    {
        return view('privacy', ['activeNav' => null]);
    }

    public function terms(): View
    {
        return view('terms', ['activeNav' => null]);
    }
}
