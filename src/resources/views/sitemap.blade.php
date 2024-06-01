<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ env('APP_URL') }}</loc>
        <lastmod>2022-07-01</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>{{ env('APP_URL') }}/blog</loc>
        <lastmod>2022-07-01</lastmod>
        <changefreq>daily</changefreq>
        <priority>1</priority>
    </url>
    @foreach ($pages as $page)
        <url>
            <loc>{{ env('APP_URL') }}/blog/{{ $page->id }}/{{ $page->slug }}</loc>
            <lastmod>{{ $page->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>