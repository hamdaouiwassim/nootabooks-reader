<?php

use App\Http\Controllers\AuthPageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WriterFollowController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/discover', [PageController::class, 'discover'])->name('discover');
Route::get('/categories', [PageController::class, 'categories'])->name('categories');
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

Route::get('/community', [PageController::class, 'community'])->name('community');
Route::get('/clubs', [PageController::class, 'readingClubs'])->name('reading-clubs');
Route::get('/clubs/{club?}', [PageController::class, 'clubDetails'])->name('club-details');
Route::get('/discussions/{discussion?}', [PageController::class, 'discussionDetails'])->name('discussion-details');

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
