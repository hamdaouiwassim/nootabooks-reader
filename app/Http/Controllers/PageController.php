<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Club;
use App\Models\Discussion;
use App\Models\Quote;
use App\Models\User;
use App\Models\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $data = Cache::remember('home.page.data', now()->addHours(6), function () {
            $trendingBooks = Book::published()->with('writer')
                ->orderByDesc('downloads_count')
                ->take(10)
                ->get();

            $similarBooks = Book::published()->with('writer')
                ->whereNotIn('id', $trendingBooks->pluck('id'))
                ->orderByDesc('rating_average')
                ->take(4)
                ->get();

            $booksCount = Book::published()->count();

            return [
                'trendingBooks' => $trendingBooks,
                'similarBooks' => $similarBooks,
                'recentBooks' => $booksCount > 10
                    ? Book::published()->with('writer')->orderByDesc('created_at')->take(10)->get()
                    : collect(),
                'categories' => Category::withCount(['books' => fn ($q) => $q->published()])
                    ->orderByDesc('books_count')
                    ->orderBy('id')
                    ->take(8)
                    ->get(),
                'popularWriters' => Writer::orderByDesc('followers_count')->take(4)->get(),
                'heroQuotes' => Quote::orderByDesc('id')->take(3)->get(),
                'booksCount' => $booksCount,
                'writersCount' => Writer::count(),
                'categoriesCount' => Category::count(),
            ];
        });

        return view('home', ['activeNav' => 'home', ...$data]);
    }

    public function discover(Request $request): View
    {
        $selectedCategorySlugs = array_filter((array) $request->input('category', []));
        $language = $request->string('lang', 'all')->toString();
        $selectedRatings = array_filter(array_map('floatval', (array) $request->input('rating', [])));
        $selectedFormats = array_values(array_intersect((array) $request->input('format', []), ['PDF', 'EPUB', 'MOBI']));
        $search = trim((string) $request->input('q', ''));
        $sort = $request->string('sort', 'popular')->toString();

        $query = Book::published()->with(['category', 'writer'])
            ->when($selectedCategorySlugs, function ($q) use ($selectedCategorySlugs) {
                $q->whereHas('category', fn ($cq) => $cq->whereIn('slug', $selectedCategorySlugs));
            })
            ->when($language === 'عربي', fn ($q) => $q->where('language', 'العربية'))
            ->when($language === 'أجنبي', fn ($q) => $q->where('language', '!=', 'العربية'))
            ->when($selectedRatings, fn ($q) => $q->where('rating_average', '>=', min($selectedRatings)))
            ->when($selectedFormats, function ($q) use ($selectedFormats) {
                $q->where(function ($fq) use ($selectedFormats) {
                    foreach ($selectedFormats as $format) {
                        $fq->orWhereJsonContains('formats', $format);
                    }
                });
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('title', 'like', "%{$search}%")
                        ->orWhereHas('writer', fn ($wq) => $wq->where('name', 'like', "%{$search}%"));
                });
            });

        match ($sort) {
            'newest' => $query->orderByDesc('published_year'),
            'rating' => $query->orderByDesc('rating_average'),
            'az' => $query->orderBy('title'),
            default => $query->orderByDesc('downloads_count'),
        };

        $books = $query->paginate(16)->withQueryString();

        return view('discover', [
            'activeNav' => 'discover',
            'books' => $books,
            'categories' => Category::orderBy('name')->get(),
            'selectedCategorySlugs' => $selectedCategorySlugs,
            'selectedLanguage' => $language,
            'selectedRatings' => $selectedRatings,
            'selectedFormats' => $selectedFormats,
            'search' => $search,
            'sort' => $sort,
        ]);
    }

    public function categories(): View
    {
        return view('categories', [
            'activeNav' => 'categories',
            'categories' => Category::withCount(['books' => fn ($q) => $q->published()])->orderBy('id')->paginate(12)->withQueryString(),
            'topCategories' => Category::withCount(['books' => fn ($q) => $q->published()])->orderByDesc('books_count')->take(5)->get(),
        ]);
    }

    public function categoryDetails(Request $request, ?string $category = null): View
    {
        $currentCategory = Category::withCount(['books' => fn ($q) => $q->published()])
            ->where('slug', $category ?? 'novels')
            ->firstOrFail();

        $search = trim((string) $request->input('q', ''));

        $categoryBooks = $currentCategory->books()
            ->published()
            ->with('writer')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('title', 'like', "%{$search}%")
                        ->orWhereHas('writer', fn ($wq) => $wq->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('rating_average')
            ->paginate(9)
            ->withQueryString();

        $similarCategories = Category::withCount(['books' => fn ($q) => $q->published()])
            ->where('id', '!=', $currentCategory->id)
            ->orderByDesc('books_count')
            ->take(5)
            ->get();

        return view('category-details', [
            'activeNav' => 'categories',
            'currentCategory' => $currentCategory,
            'categoryBooks' => $categoryBooks,
            'similarCategories' => $similarCategories,
            'search' => $search,
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
            'writers' => Writer::withCount(['books' => fn ($q) => $q->published()])->orderBy('name')->paginate(9)->withQueryString(),
            'featuredWriter' => Writer::withCount(['books' => fn ($q) => $q->published()])->where('is_featured', true)->first(),
        ]);
    }

    public function writerDetails(?string $writer = null): View
    {
        $currentWriter = Writer::withCount(['books' => fn ($q) => $q->published()])
            ->where('slug', $writer ?? 'ahmed-mourad')
            ->firstOrFail();

        $writerBooks = $currentWriter->books()
            ->published()
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
        $currentBook = Book::published()->with(['category', 'writer'])
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

        $similarBooks = Book::published()->with('writer')
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

    public function read(?string $book = null): View|RedirectResponse
    {
        $currentBook = Book::published()->with('writer')
            ->where('slug', $book ?? 'blue-elephant')
            ->firstOrFail();

        if ($currentBook->is_coming_soon) {
            return redirect()->route('book-details', $currentBook->slug)
                ->with('info', 'هذا الكتاب سيتوفر قريبًا، لا يمكن قراءته الآن.');
        }

        return view('read', ['currentBook' => $currentBook]);
    }

    public function community(Request $request): View
    {
        $chatBook = $request->filled('book')
            ? Book::published()->where('slug', $request->string('book'))->first()
            : null;

        $tag = $request->filled('tag') ? trim((string) $request->string('tag')) : null;

        $discussions = Discussion::with(['user', 'book'])
            ->withCount(['likedBy', 'comments'])
            ->when($chatBook, fn ($q) => $q->where('book_id', $chatBook->id))
            ->when($tag, fn ($q) => $q->where('body', 'like', "%#{$tag}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $likedDiscussionIds = $request->user()
            ? $request->user()->likedDiscussions()->pluck('discussions.id')->all()
            : [];

        return view('community', [
            'activeNav' => 'community',
            'chatBook' => $chatBook,
            'tag' => $tag,
            'discussions' => $discussions,
            'likedDiscussionIds' => $likedDiscussionIds,
            'discussionsCount' => Discussion::count(),
            'membersCount' => User::count(),
            'postsTodayCount' => Discussion::whereDate('created_at', today())->count(),
            'topClubs' => Club::withCount('members')->orderByDesc('members_count')->take(3)->get(),
            'topContributors' => User::where('points', '>', 0)->orderByDesc('points')->take(4)->get(),
            'trendingTags' => $this->trendingTags(),
            'clubsCount' => Club::count(),
        ]);
    }

    /**
     * Ranks #hashtags found in recent discussion posts by frequency —
     * computed on the fly rather than via a dedicated tags table, since the
     * discussion volume at this app's scale doesn't warrant one.
     */
    private function trendingTags(int $limit = 6): array
    {
        $counts = [];

        foreach (Discussion::latest()->take(200)->pluck('body') as $body) {
            preg_match_all('/#([\p{Arabic}\p{L}0-9_]+)/u', $body, $matches);
            foreach ($matches[1] as $tag) {
                $counts[$tag] = ($counts[$tag] ?? 0) + 1;
            }
        }

        arsort($counts);

        return array_slice(array_keys($counts), 0, $limit);
    }

    public function readingClubs(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $sort = $request->string('sort', 'popular')->toString();

        $clubs = Club::withCount('members')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($sort === 'newest', fn ($q) => $q->orderByDesc('created_at'))
            ->when($sort !== 'newest', fn ($q) => $q->orderByDesc('members_count'))
            ->paginate(12)
            ->withQueryString();

        return view('reading-clubs', [
            'activeNav' => 'community',
            'clubs' => $clubs,
            'search' => $search,
            'sort' => $sort,
            'books' => Book::published()->orderBy('title')->get(['id', 'title', 'slug']),
        ]);
    }

    public function clubDetails(Request $request, ?string $club = null): View
    {
        $currentClub = Club::withCount(['members', 'discussions'])
            ->when($club, fn ($q) => $q->where('slug', $club))
            ->when(! $club, fn ($q) => $q->orderBy('id'))
            ->with('creator')
            ->firstOrFail();

        $currentBook = $currentClub->currentBook();
        $pastBooks = $currentClub->pastBooks()->get();

        $discussions = $currentClub->discussions()
            ->with(['user', 'book'])
            ->withCount(['likedBy', 'comments'])
            ->latest()
            ->take(10)
            ->get();

        $members = $currentClub->members()->orderByPivot('created_at')->take(12)->get();

        $user = $request->user();
        $isMember = $user ? $currentClub->members()->where('user_id', $user->id)->exists() : false;
        $isOwner = $user && $currentClub->created_by === $user->id;

        $likedDiscussionIds = $user ? $user->likedDiscussions()->pluck('discussions.id')->all() : [];

        return view('club-details', [
            'activeNav' => 'community',
            'club' => $currentClub,
            'currentBook' => $currentBook,
            'pastBooks' => $pastBooks,
            'discussions' => $discussions,
            'members' => $members,
            'isMember' => $isMember,
            'isOwner' => $isOwner,
            'likedDiscussionIds' => $likedDiscussionIds,
        ]);
    }

    public function discussionDetails(Request $request, ?string $discussion = null): View|RedirectResponse
    {
        $currentDiscussion = is_numeric($discussion)
            ? Discussion::with(['user', 'book', 'club'])->withCount('likedBy')->find((int) $discussion)
            : null;

        if (! $currentDiscussion) {
            return redirect()->route('community');
        }

        $comments = $currentDiscussion->topLevelComments()
            ->with('user')
            ->withCount('likedBy')
            ->with(['replies' => fn ($q) => $q->with('user')->withCount('likedBy')->oldest()])
            ->oldest()
            ->get();

        $user = $request->user();
        $likedDiscussionIds = $user ? $user->likedDiscussions()->pluck('discussions.id')->all() : [];
        $likedCommentIds = $user ? $user->likedComments()->pluck('discussion_comments.id')->all() : [];

        $relatedDiscussions = Discussion::with('book')
            ->where('id', '!=', $currentDiscussion->id)
            ->when(
                $currentDiscussion->book_id,
                fn ($q) => $q->where('book_id', $currentDiscussion->book_id),
                fn ($q) => $currentDiscussion->club_id
                    ? $q->where('club_id', $currentDiscussion->club_id)
                    : $q
            )
            ->withCount('comments')
            ->latest()
            ->take(3)
            ->get();

        if ($relatedDiscussions->isEmpty()) {
            $relatedDiscussions = Discussion::where('id', '!=', $currentDiscussion->id)
                ->withCount('comments')
                ->latest()
                ->take(3)
                ->get();
        }

        return view('discussion-details', [
            'activeNav' => 'community',
            'discussion' => $currentDiscussion,
            'comments' => $comments,
            'likedDiscussionIds' => $likedDiscussionIds,
            'likedCommentIds' => $likedCommentIds,
            'relatedDiscussions' => $relatedDiscussions,
        ]);
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
