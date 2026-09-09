<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
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

        return view('admin.statistics', [
            'activeNav' => 'statistics',
            'reviewsCount' => Review::count(),
            'averageRating' => Review::avg('rating') ?? 0,
            'followsCount' => DB::table('following')->count(),
            'newUsersThisMonth' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'monthlySignups' => $monthlySignups,
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
}
