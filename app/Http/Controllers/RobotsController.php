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
            'Disallow: /community',
            'Disallow: /clubs',
            'Disallow: /discussions',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n", 200)->header('Content-Type', 'text/plain');
    }
}
