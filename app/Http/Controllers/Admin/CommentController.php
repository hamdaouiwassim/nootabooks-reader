<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscussionComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $comments = DiscussionComment::with(['user', 'discussion'])
            ->withCount('likedBy')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q');
                $query->where(function ($q) use ($term) {
                    $q->where('body', 'like', "%{$term}%")
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.comments.index', [
            'activeNav' => 'comments',
            'comments' => $comments,
            'totalComments' => DiscussionComment::count(),
        ]);
    }

    public function destroy(Request $request, DiscussionComment $comment): RedirectResponse
    {
        $discussionId = $comment->discussion_id;
        $comment->delete();

        if ($request->query('from') === 'discussion') {
            return redirect()->route('admin.discussions.show', $discussionId)->with('status', 'تم حذف التعليق بنجاح');
        }

        return redirect()->route('admin.comments.index')->with('status', 'تم حذف التعليق بنجاح');
    }
}
