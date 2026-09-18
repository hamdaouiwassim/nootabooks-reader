<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Club;
use App\Models\Discussion;
use App\Models\Writer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap.xml', now()->addHours(6), function () {
            // Same indexability rules used for robots on each page (see
            // PageController::community()/readingClubs() and
            // Discussion::scopeIndexable()/Club::scopeIndexable()) — a URL
            // never appears here while also being noindex on the page itself.
            $hasMeaningfulCommunityActivity = Discussion::indexable()->exists() || Club::indexable()->exists();
            $hasIndexableClubs = Club::indexable()->exists();

            $staticPages = array_values(array_filter([
                ['url' => route('home'), 'priority' => '1.0'],
                ['url' => route('discover'), 'priority' => '0.8'],
                ['url' => route('categories'), 'priority' => '0.7'],
                ['url' => route('writers'), 'priority' => '0.7'],
                $hasMeaningfulCommunityActivity ? ['url' => route('community'), 'priority' => '0.6'] : null,
                $hasIndexableClubs ? ['url' => route('reading-clubs'), 'priority' => '0.5'] : null,
                ['url' => route('contact'), 'priority' => '0.3'],
                ['url' => route('privacy'), 'priority' => '0.2'],
                ['url' => route('terms'), 'priority' => '0.2'],
            ]));

            $books = Book::published()->select('slug', 'updated_at')->orderByDesc('updated_at')->get();
            // Mirrors writerDetails()'s indexability rule: a profile with no
            // published books and no bio is a thin, noindexed page.
            $writers = Writer::indexable()
                ->select('slug', 'updated_at')
                ->orderByDesc('updated_at')
                ->get();
            // Only categories with at least one published book resolve to an
            // indexable page (see PageController::categoryDetails()) — an
            // empty category is noindexed, so it has no place in the sitemap.
            $categories = Category::whereHas('books', fn ($q) => $q->published())
                ->select('slug', 'updated_at')
                ->orderByDesc('updated_at')
                ->get();

            $discussions = Discussion::indexable()->select('id', 'updated_at')->orderByDesc('updated_at')->get();
            $clubs = Club::indexable()->select('slug', 'updated_at')->orderByDesc('updated_at')->get();

            return view('sitemap', [
                'staticPages' => $staticPages,
                'books' => $books,
                'writers' => $writers,
                'categories' => $categories,
                'discussions' => $discussions,
                'clubs' => $clubs,
            ])->render();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
