<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\DownloadLog;
use App\Models\Review;
use App\Models\User;
use App\Models\Writer;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index(): View
    {
        $monthlySignups = $this->monthlyCounts(User::query());
        $dailyDownloads = DownloadLog::perDay(30);
        $dailyBooksAdded = Book::perDay(30);
        $topDownloaded48h = $this->topDownloaded48h();

        return view('admin.statistics', [
            'activeNav' => 'statistics',
            'reviewsCount' => Review::count(),
            'averageRating' => Review::avg('rating') ?? 0,
            'followsCount' => DB::table('following')->count(),
            'newUsersThisMonth' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'monthlySignups' => $monthlySignups,
            'dailyDownloads' => $dailyDownloads,
            'dailyBooksAdded' => $dailyBooksAdded,
            'topDownloaded48h' => $topDownloaded48h,
            'topCategories' => Category::withCount('books')
                ->orderByDesc('books_count')
                ->take(5)
                ->get(),
            'topWriters' => Writer::orderByDesc('followers_count')
                ->take(5)
                ->get(),
            'topBooks' => Book::with(['writer', 'category'])
                ->orderByDesc('downloads_count')
                ->take(5)
                ->get(),
        ]);
    }

    /**
     * Real signups per month for the last 6 calendar months, including months
     * with zero signups (so the chart's x-axis stays a continuous timeline
     * instead of silently skipping quiet months).
     */
    private function monthlyCounts($query): array
    {
        $counts = $query->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $months[] = [
                'label' => $date->translatedFormat('M'),
                'count' => (int) ($counts[$key] ?? 0),
            ];
        }

        return $months;
    }

    /**
     * Top 10 books by download count in the last 48 hours specifically —
     * distinct from Book.downloads_count, which is a lifetime running total
     * and can't answer "trending right now". Two queries: an aggregate count
     * from download_logs, then a batch fetch of just those books.
     */
    private function topDownloaded48h()
    {
        $recentCounts = DownloadLog::query()
            ->selectRaw('book_id, COUNT(*) as downloads')
            ->where('created_at', '>=', now()->subHours(48))
            ->groupBy('book_id')
            ->orderByDesc('downloads')
            ->take(10)
            ->pluck('downloads', 'book_id');

        if ($recentCounts->isEmpty()) {
            return collect();
        }

        return Book::with(['writer', 'category'])
            ->whereIn('id', $recentCounts->keys())
            ->get()
            ->each(fn ($book) => $book->recent_downloads = $recentCounts[$book->id])
            ->sortByDesc('recent_downloads')
            ->values();
    }
}
