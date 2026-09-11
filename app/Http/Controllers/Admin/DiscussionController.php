<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discussion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    public function index(Request $request): View
    {
        $discussions = Discussion::with(['user', 'book', 'club'])
            ->withCount(['likedBy', 'comments'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q');
                $query->where(function ($q) use ($term) {
                    $q->where('body', 'like', "%{$term}%")
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.discussions.index', [
            'activeNav' => 'discussions',
            'discussions' => $discussions,
            'totalDiscussions' => Discussion::count(),
        ]);
    }

    public function show(Discussion $discussion): View
    {
        $discussion->load(['user', 'book', 'club']);

        $comments = $discussion->topLevelComments()
            ->with(['user', 'replies.user'])
            ->oldest()
            ->get();

        return view('admin.discussions.show', [
            'activeNav' => 'discussions',
            'discussion' => $discussion,
            'comments' => $comments,
        ]);
    }

    public function destroy(Discussion $discussion): RedirectResponse
    {
        $discussion->delete();

        return redirect()->route('admin.discussions.index')->with('status', 'تم حذف المناقشة بنجاح');
    }
}
