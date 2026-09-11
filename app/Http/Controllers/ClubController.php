<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Club;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:2000'],
            'book' => ['required', 'string', 'exists:books,slug'],
        ], [
            'name.required' => 'اسم النادي مطلوب.',
            'description.required' => 'وصف النادي مطلوب.',
            'book.required' => 'يجب اختيار كتاب يبدأ به النادي.',
            'book.exists' => 'الكتاب المحدد غير موجود.',
        ]);

        $club = Club::create([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'],
            'created_by' => $request->user()->id,
        ]);

        $club->members()->attach($request->user()->id, ['role' => 'owner']);

        $book = Book::published()->where('slug', $validated['book'])->first();
        if ($book) {
            $club->books()->attach($book->id, ['started_at' => now()]);
        }

        return redirect()->route('club-details', $club->slug)->with('status', 'تم إنشاء النادي بنجاح');
    }

    public function toggleJoin(Request $request, Club $club): RedirectResponse
    {
        $user = $request->user();

        if ($club->created_by === $user->id) {
            return back()->with('error', 'لا يمكن لمنشئ النادي مغادرته.');
        }

        if ($club->members()->where('user_id', $user->id)->exists()) {
            $club->members()->detach($user->id);
            $status = "تم مغادرة نادي \"{$club->name}\"";
        } else {
            $club->members()->attach($user->id, ['role' => 'member']);
            $status = "تم الانضمام إلى نادي \"{$club->name}\" بنجاح";
        }

        return back()->with('status', $status);
    }
}
