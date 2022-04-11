<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($categories as $post)
        <url>
            <loc>{{ $post->url }}</loc>
            <lastmod>{{ $post->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>yearly</changefreq>
            <priority>0.9</priority>
            <image:image>
                <image:loc>{{ env('APP_URL') . '/' . $post->cover_photo }}</image:loc>
            </image:image>
        </url>
    @endforeach
</urlset>