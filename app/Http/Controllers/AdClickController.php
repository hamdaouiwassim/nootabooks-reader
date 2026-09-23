<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;

class AdClickController extends Controller
{
    /**
     * Counts the click, then sends the visitor on to the ad's real
     * destination. The route is signed (not time-limited like a book
     * download link) so the {ad} id can't be tampered with, while still
     * working indefinitely for a link that may sit in cached/shared HTML.
     */
    public function redirect(Advertisement $ad): RedirectResponse
    {
        $ad->increment('clicks_count');

        return redirect()->away($ad->target_url);
    }
}
