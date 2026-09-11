<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClubRequest;
use App\Http\Requests\Admin\UpdateClubRequest;
use App\Models\Book;
use App\Models\Club;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClubController extends Controller
{
    public function index(Request $request): View
    {
        $clubs = Club::withCount(['members', 'discussions'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q');
                $query->where('name', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.clubs.index', [
            'activeNav' => 'clubs',
            'clubs' => $clubs,
            'totalClubs' => Club::count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.clubs.create', ['activeNav' => 'clubs']);
    }

    public function store(StoreClubRequest $request): RedirectResponse
    {
        // No owner is attached here — admin-created clubs are official/
        // unowned (created_by stays null), unlike reader-created ones via
        // the public "create a club" form, which attach the creator as owner.
        $club = Club::create($request->validated());

        return redirect()->route('admin.clubs.edit', $club)->with('status', 'تم إنشاء النادي بنجاح');
    }

    public function edit(Club $club): View
    {
        return view('admin.clubs.edit', [
            'activeNav' => 'clubs',
            'club' => $club->loadCount(['members', 'discussions']),
            'currentBook' => $club->currentBook(),
            'pastBooks' => $club->pastBooks()->get(),
            'books' => Book::published()->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(UpdateClubRequest $request, Club $club): RedirectResponse
    {
        $club->update($request->validated());

        return redirect()->route('admin.clubs.edit', $club)->with('status', 'تم حفظ التعديلات بنجاح');
    }

    public function destroy(Club $club): RedirectResponse
    {
        $club->delete();

        return redirect()->route('admin.clubs.index')->with('status', 'تم حذف النادي بنجاح');
    }

    public function setCurrentBook(Request $request, Club $club): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'integer', 'exists:books,id'],
        ], [
            'book_id.required' => 'يجب اختيار كتاب.',
        ]);

        // Close out the currently-open reading row specifically (not just by
        // book_id via updateExistingPivot), since a club can legitimately
        // read the same book more than once across its history.
        DB::table('club_books')
            ->where('club_id', $club->id)
            ->whereNull('finished_at')
            ->update(['finished_at' => now()]);

        $club->books()->attach($validated['book_id'], ['started_at' => now()]);

        return redirect()->route('admin.clubs.edit', $club)->with('status', 'تم تحديث الكتاب الحالي للنادي');
    }
}
