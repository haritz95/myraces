<?php

namespace App\Http\Controllers;

use App\Models\RaceEvent;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $events = RaceEvent::whereNotIn('status', ['pending', 'rejected'])
            ->whereNotNull('slug')
            ->orderBy('event_date', 'desc')
            ->get(['slug', 'updated_at', 'event_date']);

        $content = view('sitemap', compact('events'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
