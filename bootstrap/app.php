<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // RedirectIfAuthenticated only knows routes named "dashboard" or
        // "home"; the admin dashboard is named "admin.dashboard", so an
        // already-logged-in admin hitting /admin/login was falling through
        // to the reader's home page instead of /admin.
        $middleware->redirectUsersTo(fn ($request) => $request->is('admin/*')
            ? route('admin.dashboard')
            : route('home'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // The whole upload (file + other form fields) exceeded PHP's
        // post_max_size before the request even reached validation — PHP
        // discards $_POST/$_FILES in that case, so this must be caught here
        // rather than as a normal validation rule.
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            return back()->withInput($request->except(['cover_image', 'book_file']))->withErrors([
                'upload' => 'الملف الذي حاولت رفعه كبير جدًا ولا يمكن للخادم استقباله، يرجى رفع ملف أصغر حجمًا أو التواصل مع الدعم الفني.',
            ]);
        });
    })->create();
