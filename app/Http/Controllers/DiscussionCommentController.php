<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DiscussionCommentController extends Controller
{
    public function store(Request $request, Discussion $discussion): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
        ], [
            'body.required' => 'اكتب تعليقًا قبل الإرسال.',
            'body.max' => 'التعليق طويل جدًا (الحد الأقصى 2000 حرف).',
        ]);

        $parentId = null;
        if (! empty($validated['parent_id'])) {
            $parentId = $discussion->comments()->whereNull('parent_id')
                ->where('id', $validated['parent_id'])
                ->value('id');
        }

        $discussion->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $parentId,
            'body' => $validated['body'],
        ]);

        $request->user()->increment('points', 2);

        return back()->with('status', 'تم إضافة تعليقك بنجاح')->withFragment('comments');
    }

    public function toggleLike(Request $request, DiscussionComment $comment): RedirectResponse
    {
        $user = $request->user();

        if ($comment->likedBy()->where('user_id', $user->id)->exists()) {
            $comment->likedBy()->detach($user);
        } else {
            $comment->likedBy()->attach($user);
        }

        return back()->withFragment('comments');
    }
}
