<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>{{ url('/events') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    @foreach($events as $event)
    <url>
        <loc>{{ route('events.show', $event->slug) }}</loc>
        <lastmod>{{ ($event->updated_at ?? $event->event_date)?->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

</urlset>
