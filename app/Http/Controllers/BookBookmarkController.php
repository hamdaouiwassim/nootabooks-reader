<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookBookmarkController extends Controller
{
    public function toggle(Request $request, Book $book): RedirectResponse
    {
        $user = $request->user();

        if ($user->bookmarks()->where('book_id', $book->id)->exists()) {
            $user->bookmarks()->detach($book);
            $status = "تمت إزالة \"{$book->title}\" من المفضلة";
        } else {
            $user->bookmarks()->attach($book);
            $status = "تمت إضافة \"{$book->title}\" إلى المفضلة";
        }

        return back()->with('status', $status);
    }
}
