<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClubController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscussionController;
use App\Http\Controllers\Admin\FileAuditController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\SearchLogController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WriterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1');

    Route::get('/login/verify', [AuthenticatedSessionController::class, 'showVerify'])->name('login.verify');
    Route::post('/login/verify', [AuthenticatedSessionController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('login.verify.store');
    Route::post('/login/verify/resend', [AuthenticatedSessionController::class, 'resend'])
        ->middleware('throttle:5,1')
        ->name('login.verify.resend');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:admin')
    ->name('logout');

Route::middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::get('/books/{book}/stats', [BookController::class, 'stats'])->name('books.stats');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::put('/books/{book}/copyright-block', [BookController::class, 'toggleCopyrightBlock'])->name('books.copyright-block');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    Route::get('/writers', [WriterController::class, 'index'])->name('writers.index');
    Route::get('/writers/create', [WriterController::class, 'create'])->name('writers.create');
    Route::post('/writers', [WriterController::class, 'store'])->name('writers.store');
    Route::get('/writers/{writer}/edit', [WriterController::class, 'edit'])->name('writers.edit');
    Route::put('/writers/{writer}', [WriterController::class, 'update'])->name('writers.update');
    Route::delete('/writers/{writer}', [WriterController::class, 'destroy'])->name('writers.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
    Route::get('/quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::get('/quotes/{quote}/edit', [QuoteController::class, 'edit'])->name('quotes.edit');
    Route::put('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
    Route::delete('/quotes/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');

    Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::get('/clubs/create', [ClubController::class, 'create'])->name('clubs.create');
    Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
    Route::get('/clubs/{club}/edit', [ClubController::class, 'edit'])->name('clubs.edit');
    Route::put('/clubs/{club}', [ClubController::class, 'update'])->name('clubs.update');
    Route::post('/clubs/{club}/current-book', [ClubController::class, 'setCurrentBook'])->name('clubs.set-current-book');
    Route::delete('/clubs/{club}', [ClubController::class, 'destroy'])->name('clubs.destroy');

    Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::get('/discussions/{discussion}', [DiscussionController::class, 'show'])->name('discussions.show');
    Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');

    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/book-reports', [BookReportController::class, 'index'])->name('book-reports.index');
    Route::get('/book-reports/{bookReport}', [BookReportController::class, 'show'])->name('book-reports.show');
    Route::put('/book-reports/{bookReport}/reviewed', [BookReportController::class, 'markReviewed'])->name('book-reports.mark-reviewed');
    Route::delete('/book-reports/{bookReport}', [BookReportController::class, 'destroy'])->name('book-reports.destroy');

    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    Route::get('/file-audit', [FileAuditController::class, 'index'])->name('file-audit');

    Route::get('/search-logs', [SearchLogController::class, 'index'])->name('search-logs.index');
    Route::delete('/search-logs/{searchLog}', [SearchLogController::class, 'destroy'])->name('search-logs.destroy');
    Route::delete('/search-logs', [SearchLogController::class, 'clear'])->name('search-logs.clear');

    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup', [BackupController::class, 'create'])->name('backup.create')->middleware('throttle:3,10');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});
