<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            '',
            'Disallow: /admin/',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /profile',
            'Disallow: /settings',
            'Disallow: /notifications',
            'Disallow: /my-library',
            // /community, /clubs, and /discussions/{id} are intentionally
            // crawlable — each sets its own index/noindex meta tag depending
            // on whether it currently has meaningful public content (see
            // PageController::community()/readingClubs()/clubDetails()/
            // discussionDetails()), which only works if Google can actually
            // fetch the page to read that tag.
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n", 200)->header('Content-Type', 'text/plain');
    }
}
