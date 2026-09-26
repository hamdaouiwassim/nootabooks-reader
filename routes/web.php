<?php

use App\Http\Controllers\AdClickController;
use App\Http\Controllers\AuthPageController;
use App\Http\Controllers\BookBookmarkController;
use App\Http\Controllers\BookDownloadController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\DiscussionCommentController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WriterFollowController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');

Route::get('/discover', [PageController::class, 'discover'])->name('discover');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])
    ->middleware('throttle:30,1')
    ->name('search.autocomplete');
Route::get('/categories', [PageController::class, 'categories'])->name('categories');
Route::get('/categories/{category?}', [PageController::class, 'categoryDetails'])->name('category-details');
Route::get('/my-library', [PageController::class, 'myLibrary'])->name('my-library');

// Old /writers URLs are already indexed/linked externally — 301 them to the
// new /authors path instead of letting them 404 (see routes below).
Route::redirect('/writers', '/authors', 301);
Route::get('/writers/{writer}', function (string $writer) {
    return redirect()->route('writer-details', $writer, 301);
});

Route::get('/authors', [PageController::class, 'writers'])->name('writers');
Route::get('/authors/{writer?}', [PageController::class, 'writerDetails'])->name('writer-details');
Route::post('/authors/{writer}/follow', [WriterFollowController::class, 'toggle'])
    ->middleware('auth')
    ->name('writers.follow');

Route::get('/books/{book?}', [PageController::class, 'bookDetails'])->name('book-details');
Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');
Route::get('/read/{book?}', [PageController::class, 'read'])->name('read');
Route::get('/books/{book}/stream', [BookDownloadController::class, 'stream'])->name('books.stream')->middleware('signed');
Route::get('/books/{book}/download', [BookDownloadController::class, 'download'])->name('books.download')->middleware('signed');
Route::get('/ads/{ad}/click', [AdClickController::class, 'redirect'])->name('ads.click')->middleware('signed');
Route::post('/books/{book}/bookmark', [BookBookmarkController::class, 'toggle'])
    ->middleware('auth')
    ->name('books.bookmark');
Route::get('/books/{book}/report', [BookReportController::class, 'create'])->name('books.report');
Route::post('/books/{book}/report', [BookReportController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('books.report.store');

Route::get('/community', [PageController::class, 'community'])->name('community');
Route::post('/community/discussions', [DiscussionController::class, 'store'])
    ->middleware('auth')
    ->name('discussions.store');
Route::post('/discussions/{discussion}/like', [DiscussionController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('discussions.like');
Route::get('/clubs', [PageController::class, 'readingClubs'])->name('reading-clubs');
Route::post('/clubs', [ClubController::class, 'store'])
    ->middleware('auth')
    ->name('clubs.store');
Route::get('/clubs/{club?}', [PageController::class, 'clubDetails'])->name('club-details');
Route::post('/clubs/{club}/join', [ClubController::class, 'toggleJoin'])
    ->middleware('auth')
    ->name('clubs.join');
Route::get('/discussions/{discussion?}', [PageController::class, 'discussionDetails'])->name('discussion-details');
Route::post('/discussions/{discussion}/comments', [DiscussionCommentController::class, 'store'])
    ->middleware('auth')
    ->name('discussions.comments.store');
Route::post('/comments/{comment}/like', [DiscussionCommentController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('comments.like');

Route::get('/profile', [PageController::class, 'profile'])->middleware('auth')->name('profile');
Route::get('/settings', [PageController::class, 'settings'])->name('settings');
Route::get('/notifications', [PageController::class, 'notifications'])->name('notifications');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])
    ->middleware('throttle:5,60')
    ->name('contact.submit');

Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/copyright', [PageController::class, 'copyright'])->name('copyright');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

Route::get('/login', [AuthPageController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthPageController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.submit');
Route::get('/register', [AuthPageController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthPageController::class, 'register'])
    ->middleware('throttle:5,60')
    ->name('register.submit');
Route::get('/register/verify', [AuthPageController::class, 'showVerifyEmail'])->name('register.verify');
Route::post('/register/verify', [AuthPageController::class, 'verifyEmail'])
    ->middleware('throttle:10,1')
    ->name('register.verify.submit');
Route::post('/register/verify/resend', [AuthPageController::class, 'resendVerification'])
    ->middleware('throttle:5,1')
    ->name('register.verify.resend');

Route::get('/forgot-password', [AuthPageController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthPageController::class, 'submitForgotPassword'])
    ->middleware('throttle:5,60')
    ->name('forgot-password.submit');
Route::get('/forgot-password/verify', [AuthPageController::class, 'showResetPassword'])->name('reset-password');
Route::post('/forgot-password/verify', [AuthPageController::class, 'submitResetPassword'])
    ->middleware('throttle:10,1')
    ->name('reset-password.submit');
Route::post('/forgot-password/resend', [AuthPageController::class, 'resendResetCode'])
    ->middleware('throttle:5,1')
    ->name('reset-password.resend');

Route::post('/logout', [AuthPageController::class, 'logout'])->name('logout');
