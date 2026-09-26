<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Import\BookCsvImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookImportController extends Controller
{
    public function create(): View
    {
        return view('admin.books.import', ['activeNav' => 'books']);
    }

    public function store(Request $request, BookCsvImporter $importer): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'csv_file.mimes' => 'يجب أن يكون الملف بصيغة CSV.',
        ]);

        $results = $importer->import($request->file('csv_file')->getRealPath());

        return redirect()->route('admin.books.import.create')->with('importResults', $results);
    }
}
