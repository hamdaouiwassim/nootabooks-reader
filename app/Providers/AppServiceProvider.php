<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Writer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin.partials.admin-sidebar', function ($view) {
            $view->with([
                'sidebarBooksCount' => Book::count(),
                'sidebarWritersCount' => Writer::count(),
                'sidebarCategoriesCount' => Category::count(),
            ]);
        });
    }
}
