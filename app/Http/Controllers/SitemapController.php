<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Writer;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticPages = [
            ['url' => route('home'), 'priority' => '1.0'],
            ['url' => route('discover'), 'priority' => '0.8'],
            ['url' => route('categories'), 'priority' => '0.7'],
            ['url' => route('writers'), 'priority' => '0.7'],
            ['url' => route('contact'), 'priority' => '0.3'],
            ['url' => route('privacy'), 'priority' => '0.2'],
            ['url' => route('terms'), 'priority' => '0.2'],
        ];

        $books = Book::select('slug', 'updated_at')->orderByDesc('updated_at')->get();
        $writers = Writer::select('slug', 'updated_at')->orderByDesc('updated_at')->get();
        $categories = Category::select('slug', 'updated_at')->orderByDesc('updated_at')->get();

        $xml = view('sitemap', [
            'staticPages' => $staticPages,
            'books' => $books,
            'writers' => $writers,
            'categories' => $categories,
        ])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
