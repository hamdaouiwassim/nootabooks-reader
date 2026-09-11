<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Club;
use App\Models\Discussion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'book' => ['nullable', 'string', 'exists:books,slug'],
            'club' => ['nullable', 'string', 'exists:clubs,slug'],
        ], [
            'body.required' => 'اكتب شيئًا قبل النشر.',
            'body.max' => 'المنشور طويل جدًا (الحد الأقصى 2000 حرف).',
        ]);

        $book = $validated['book'] ?? null
            ? Book::published()->where('slug', $validated['book'])->first()
            : null;

        $club = $validated['club'] ?? null
            ? Club::where('slug', $validated['club'])->first()
            : null;

        $request->user()->discussions()->create([
            'book_id' => $book?->id,
            'club_id' => $club?->id,
            'body' => $validated['body'],
        ]);

        $request->user()->increment('points', 5);

        return back()->with('status', 'تم نشر مشاركتك بنجاح');
    }

    public function toggleLike(Request $request, Discussion $discussion): RedirectResponse
    {
        $user = $request->user();

        if ($discussion->likedBy()->where('user_id', $user->id)->exists()) {
            $discussion->likedBy()->detach($user);
        } else {
            $discussion->likedBy()->attach($user);
        }

        return back();
    }
}
