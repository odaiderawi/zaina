<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach ($news as $post)
        <url>
            <loc>{{ $post->url }}</loc>
            <lastmod>
                @if($post->updated_at)
                    {{
                        $post->updated_at->tz('UTC')->toAtomString()
                    }}
                @else

                @endif
            </lastmod>
            <changefreq>{{ $time }}</changefreq>
            <priority>{{ $priority }}</priority>
            <image:image>
                <image:loc>{{ env('APP_URL') . '/' . $post->image }}</image:loc>
            </image:image>
        </url>
    @endforeach
</urlset>