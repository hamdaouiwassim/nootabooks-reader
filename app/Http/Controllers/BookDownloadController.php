<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;

class BookDownloadController extends Controller
{
    public function download(Book $book): RedirectResponse
    {
        abort_unless($book->file_path, 404);

        $book->increment('downloads_count');

        return redirect()->away($book->file_url);
    }
}
