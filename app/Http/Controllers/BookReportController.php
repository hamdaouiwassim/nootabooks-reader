<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookReportController extends Controller
{
    public function create(Book $book): View
    {
        return view('book-report', [
            'activeNav' => null,
            'currentBook' => $book,
        ]);
    }

    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'reporter_name' => ['required', 'string', 'max:255'],
            'reporter_email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        BookReport::create([
            'book_id' => $book->id,
            'user_id' => auth()->id(),
            'reporter_name' => $validated['reporter_name'],
            'reporter_email' => $validated['reporter_email'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('book-details', $book->slug)
            ->with('status', 'تم استلام بلاغك بخصوص حقوق النشر، سيقوم فريقنا بمراجعته في أقرب وقت.');
    }
}
