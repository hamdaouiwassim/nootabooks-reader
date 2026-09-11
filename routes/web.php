<?php

use App\Http\Controllers\AuthPageController;
use App\Http\Controllers\BookDownloadController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\DiscussionCommentController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WriterFollowController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/discover', [PageController::class, 'discover'])->name('discover');
Route::get('/categories', [PageController::class, 'categories'])->name('categories');
Route::get('/categories/{category?}', [PageController::class, 'categoryDetails'])->name('category-details');
Route::get('/my-library', [PageController::class, 'myLibrary'])->name('my-library');

Route::get('/writers', [PageController::class, 'writers'])->name('writers');
Route::get('/writers/{writer?}', [PageController::class, 'writerDetails'])->name('writer-details');
Route::post('/writers/{writer}/follow', [WriterFollowController::class, 'toggle'])
    ->middleware('auth')
    ->name('writers.follow');

Route::get('/books/{book?}', [PageController::class, 'bookDetails'])->name('book-details');
Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');
Route::get('/read/{book?}', [PageController::class, 'read'])->name('read');
Route::get('/books/{book}/stream', [BookDownloadController::class, 'stream'])->name('books.stream')->middleware('signed');
Route::get('/books/{book}/download', [BookDownloadController::class, 'download'])->name('books.download')->middleware('signed');

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

Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::get('/settings', [PageController::class, 'settings'])->name('settings');
Route::get('/notifications', [PageController::class, 'notifications'])->name('notifications');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

Route::get('/login', [AuthPageController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthPageController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthPageController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthPageController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthPageController::class, 'logout'])->name('logout');
