<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'activeNav' => 'dashboard',
            'booksCount' => Book::count(),
            'categoriesCount' => Category::count(),
            'usersCount' => User::count(),
            'downloadsSum' => (int) Book::sum('downloads_count'),
            'latestBooks' => Book::with(['category', 'writer'])->latest()->limit(5)->get(),
            'latestDiscussions' => Discussion::with(['user', 'book', 'club'])->withCount('comments')->latest()->limit(5)->get(),
        ]);
    }
}
