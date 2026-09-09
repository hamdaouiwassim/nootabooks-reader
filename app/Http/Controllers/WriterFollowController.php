<?php

namespace App\Http\Controllers;

use App\Models\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WriterFollowController extends Controller
{
    public function toggle(Request $request, Writer $writer): RedirectResponse
    {
        $user = $request->user();

        if ($user->followedWriters()->where('writer_id', $writer->id)->exists()) {
            $user->followedWriters()->detach($writer);
            $status = "تم إلغاء متابعة {$writer->name}";
        } else {
            $user->followedWriters()->attach($writer);
            $status = "تمت متابعة {$writer->name} بنجاح";
        }

        return back()->with('status', $status);
    }
}
