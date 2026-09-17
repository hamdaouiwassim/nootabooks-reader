<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Club;
use App\Models\Discussion;
use App\Models\DownloadLog;
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
            ];
        });

        return view('home', ['activeNav' => 'home', ...$data]);
    }

    public function discover(Request $request): View
    {
        $selectedCategorySlugs = array_filter((array) $request->input('category', []));
        $language = $request->string('lang', 'all')->toString();
        $selectedRatings = array_filter(array_map('floatval', (array) $request->input('rating', [])));
        $search = trim((string) $request->input('q', ''));
        $sort = $request->string('sort', 'popular')->toString();

        $query = Book::published()->with(['category', 'writer'])
            ->when($selectedCategorySlugs, function ($q) use ($selectedCategorySlugs) {
                $q->whereHas('categories', fn ($cq) => $cq->whereIn('slug', $selectedCategorySlugs));
            })
            ->when($language === 'عربي', fn ($q) => $q->where('language', 'العربية'))
            ->when($language === 'إنجليزي', fn ($q) => $q->where('language', 'الإنجليزية'))
            ->when($language === 'مترجم', fn ($q) => $q->where('language', 'مترجم إلى العربية'))
            ->when($selectedRatings, fn ($q) => $q->where('rating_average', '>=', min($selectedRatings)))
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

        // Only the plain, unfiltered catalog (optionally paginated) is a
        // deliberate SEO landing page — any active search/category/language/
        // rating filter produces a near-duplicate slice of the same catalog
        // that shouldn't compete with it in search results.
        $isFiltered = (bool) ($selectedCategorySlugs || $language !== 'all' || $selectedRatings || $search !== '');

        return view('discover', [
            'activeNav' => 'discover',
            'books' => $books,
            'categories' => Category::orderBy('name')->get(),
            'selectedCategorySlugs' => $selectedCategorySlugs,
            'selectedLanguage' => $language,
            'selectedRatings' => $selectedRatings,
            'search' => $search,
            'sort' => $sort,
            'isFiltered' => $isFiltered,
        ]);
    }

    public function categories(): View
    {
        return view('categories', [
            'activeNav' => 'categories',
            // Small, slow-growing taxonomy (~16 categories) — a single page
            // beats paginating it, and populated categories are worth
            // surfacing before empty ones rather than plain insertion order.
            'categories' => Category::withCount(['books' => fn ($q) => $q->published()])
                ->orderByDesc('books_count')
                ->orderBy('name')
                ->get(),
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
            ->paginate(12)
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
            // A category with no published books yet is a thin page, and a
            // search within a category is a dynamic slice of it — neither
            // is a deliberate SEO landing page.
            'isIndexable' => $currentCategory->books_count > 0 && $search === '',
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
            'writers' => Writer::withCount(['books' => fn ($q) => $q->published()])->orderBy('name')->paginate(10)->withQueryString(),
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

        // Real categories the author's own published books actually belong
        // to — not a random/generic category list.
        $writerCategories = Category::whereHas('books', function ($q) use ($currentWriter) {
            $q->published()->where('writer_id', $currentWriter->id);
        })->get();

        return view('writer-details', [
            'activeNav' => 'writers',
            'currentWriter' => $currentWriter,
            'writerBooks' => $writerBooks,
            'similarWriters' => $similarWriters,
            'writerCategories' => $writerCategories,
            // A profile with no published books yet is a thin page unless it
            // at least has a real biography to offer.
            'isIndexable' => $currentWriter->books_count > 0 || filled($currentWriter->bio),
        ]);
    }

    public function bookDetails(?string $book = null): View
    {
        $currentBook = Book::published()->with(['category', 'categories', 'series', 'writer'])
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

        $seriesBooks = $currentBook->series_id
            ? Book::published()
                ->where('series_id', $currentBook->series_id)
                ->orderBy('series_order')
                ->get()
            : collect();

        $writerBooks = $currentBook->writer_id
            ? Book::published()
                ->where('writer_id', $currentBook->writer_id)
                ->where('id', '!=', $currentBook->id)
                // Exclude the current book's own series-mates — those are
                // already listed in the "أجزاء السلسلة" section above, so
                // showing them again here would just duplicate that list.
                ->when($currentBook->series_id, function ($q) use ($currentBook) {
                    $q->where(function ($sq) use ($currentBook) {
                        $sq->whereNull('series_id')->orWhere('series_id', '!=', $currentBook->series_id);
                    });
                })
                ->orderByDesc('rating_average')
                ->take(5)
                ->get()
            : collect();

        $similarBooks = Book::published()->with('writer')
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $currentBook->categories->pluck('id')))
            ->where('id', '!=', $currentBook->id)
            ->orderByDesc('rating_average')
            ->take(5)
            ->get();

        return view('book-details', [
            'activeNav' => null,
            'currentBook' => $currentBook,
            'ratingBreakdown' => $ratingBreakdown,
            'reviews' => $reviews,
            'seriesBooks' => $seriesBooks,
            'writerBooks' => $writerBooks,
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

        if ($currentBook->reading_disabled) {
            return redirect()->route('book-details', $currentBook->slug)
                ->with('info', 'القراءة غير متاحة لهذا الكتاب حاليًا.');
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

        // The community hub only earns a place in search results once real,
        // substantial public activity exists — never for its own sake, and
        // never for a filtered view of it (see Discussion::scopeIndexable(),
        // Club::scopeIndexable()).
        $hasMeaningfulActivity = Discussion::indexable()->exists() || Club::indexable()->exists();

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
            'isIndexable' => $hasMeaningfulActivity && ! $chatBook && ! $tag,
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
            'isIndexable' => $search === '' && $sort === 'popular' && Club::indexable()->exists(),
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
            // Mirrors Club::scopeIndexable() for this single already-loaded
            // record, avoiding a second query.
            'isIndexable' => filled($currentClub->description) || $currentClub->members_count >= 2 || $currentBook !== null,
        ]);
    }

    public function discussionDetails(Request $request, ?string $discussion = null): View
    {
        $currentDiscussion = is_numeric($discussion)
            ? Discussion::with(['user', 'book', 'club'])->withCount('likedBy')->find((int) $discussion)
            : null;

        abort_if(! $currentDiscussion, 404);

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
            'isIndexable' => $currentDiscussion->isIndexable(),
        ]);
    }

    public function profile(): View
    {
        $user = auth()->user();

        $bookmarks = $user->bookmarks()->with('writer')->orderByDesc('book_bookmarks.created_at')->get();
        $reviews = $user->reviews()->with('book.writer')->latest()->get();
        $clubs = $user->clubs()->get();
        $followedWriters = $user->followedWriters()->get();

        $downloadsCount = DownloadLog::where('user_id', $user->id)->count();
        $discussionsCount = $user->discussions()->count();
        $commentsCount = $user->comments()->count();

        return view('profile', [
            'activeNav' => null,
            'profileUser' => $user,
            'bookmarks' => $bookmarks,
            'reviews' => $reviews,
            'reviewsCount' => $reviews->count(),
            'bookmarksCount' => $bookmarks->count(),
            'clubsCount' => $clubs->count(),
            'followedWritersCount' => $followedWriters->count(),
            'downloadsCount' => $downloadsCount,
            'activity' => $this->buildProfileActivity($user, $reviews, $clubs, $followedWriters),
            'achievements' => [
                ['icon' => 'book-open-reader', 'title' => 'قارئ نهم', 'goal' => 5, 'progress' => $downloadsCount, 'description' => 'حمّل 5 كتب'],
                ['icon' => 'pen-nib', 'title' => 'ناقد أدبي', 'goal' => 3, 'progress' => $reviews->count(), 'description' => 'كتب 3 تقييمات'],
                ['icon' => 'people-group', 'title' => 'عضو فعّال', 'goal' => 1, 'progress' => $clubs->count(), 'description' => 'انضم إلى نادي قراءة'],
                ['icon' => 'comments', 'title' => 'صوت المجتمع', 'goal' => 10, 'progress' => $discussionsCount + $commentsCount, 'description' => 'شارك في 10 مناقشات وتعليقات'],
                ['icon' => 'user-plus', 'title' => 'متابع مخلص', 'goal' => 3, 'progress' => $followedWriters->count(), 'description' => 'تابع 3 مؤلفين'],
                ['icon' => 'heart', 'title' => 'جامع الكتب', 'goal' => 5, 'progress' => $bookmarks->count(), 'description' => 'أضف 5 كتب إلى المفضلة'],
            ],
        ]);
    }

    /**
     * Merges reviews, club joins, and writer follows into a single
     * reverse-chronological feed — there's no unified "activity" table, so
     * this stitches together the handful of real, timestamped user actions
     * that exist today.
     */
    private function buildProfileActivity(User $user, $reviews, $clubs, $followedWriters): array
    {
        $items = [];

        foreach ($reviews->take(10) as $review) {
            if (! $review->book) {
                continue;
            }
            $items[] = [
                'icon' => 'star',
                'type' => 'review',
                'text' => "قيّم <a href=\"".route('book-details', $review->book->slug)."\">{$review->book->title}</a> بـ {$review->rating} نجوم",
                'time' => $review->created_at,
            ];
        }

        foreach ($clubs as $club) {
            $items[] = [
                'icon' => 'people-group',
                'type' => 'club',
                'text' => "انضم إلى نادي <a href=\"".route('club-details', $club->slug)."\">{$club->name}</a>",
                'time' => $club->pivot->created_at,
            ];
        }

        foreach ($followedWriters as $writer) {
            $items[] = [
                'icon' => 'user-plus',
                'type' => 'follow',
                'text' => "بدأ متابعة <a href=\"".route('writer-details', $writer->slug)."\">{$writer->name}</a>",
                'time' => $writer->pivot->created_at,
            ];
        }

        usort($items, fn ($a, $b) => $b['time'] <=> $a['time']);

        return array_slice($items, 0, 10);
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

    public function copyright(): View
    {
        return view('copyright', ['activeNav' => null]);
    }

    public function faq(): View
    {
        return view('faq', ['activeNav' => null]);
    }
}
