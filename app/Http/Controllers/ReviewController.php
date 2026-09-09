<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $request->user()->reviews()->updateOrCreate(
            ['book_id' => $book->id],
            $validated
        );

        return back()->with('status', 'تم إضافة تقييمك بنجاح');
    }
}
