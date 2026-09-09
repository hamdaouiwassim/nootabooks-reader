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
        return view('discover', ['activeNav' => 'discover']);
    }

    public function categories(): View
    {
        return view('categories', ['activeNav' => 'categories']);
    }
    public function myLibrary(): View
    {
        return view('my-library', ['activeNav' => 'my-library']);
    }

    public function writers(): View
    {
        return view('writers', ['activeNav' => 'writers']);
    }

    public function writerDetails(?string $writer = null): View
    {
        return view('writer-details', ['activeNav' => 'writers', 'writerSlug' => $writer]);
    }

    public function bookDetails(?string $book = null): View
    {
        $currentBook = Book::with(['reviews.user'])
            ->where('slug', $book ?? 'blue-elephant')
            ->first();

        $ratingBreakdown = [];
        if ($currentBook) {
            $totalReviews = $currentBook->reviews->count();
            foreach ([5, 4, 3, 2, 1] as $stars) {
                $count = $currentBook->reviews->where('rating', $stars)->count();
                $ratingBreakdown[$stars] = $totalReviews > 0 ? round($count / $totalReviews * 100) : 0;
            }
        }

        return view('book-details', [
            'activeNav' => null,
            'bookSlug' => $book,
            'currentBook' => $currentBook,
            'ratingBreakdown' => $ratingBreakdown,
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
