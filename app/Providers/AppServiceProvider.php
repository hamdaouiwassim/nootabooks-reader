<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Quote;
use App\Models\Writer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
        Paginator::defaultView('pagination.default');

        // The same http/https mismatch that hit uploaded file URLs (APP_URL
        // set to http:// while the site is actually served over https once
        // deployed) would otherwise also leak into every route()/url() call
        // — canonical tags, OG URLs, the sitemap — causing the same
        // mixed-content class of bug there too. Matches force_https_url()'s
        // policy exactly: force https everywhere except local dev, where
        // nothing actually serves it.
        if (! app()->environment('local')) {
            URL::forceScheme('https');
        }

        View::composer('admin.partials.admin-sidebar', function ($view) {
            $view->with([
                'sidebarBooksCount' => Book::count(),
                'sidebarWritersCount' => Writer::count(),
                'sidebarCategoriesCount' => Category::count(),
                'sidebarQuotesCount' => Quote::count(),
            ]);
        });
    }
}
