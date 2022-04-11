<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">

    @foreach($videos as $post)
        <loc> {{ $post->url }}</loc>
        <video:video>
            <video:thumbnail_loc>{{ env('APP_URL') . '/' . $post->image }}</video:thumbnail_loc>
            <video:title>{{ $post->name }}</video:title>
            @if($post->source)
                <video:content_loc>{{ $post->source }}</video:content_loc>
            @else
                <video:content_loc> {{ env('APP_NAME') . '/videos' }}</video:content_loc>
            @endif
            <video:player_loc allow_embed='yes' autoplay='ap=1'>{{ $post->source }}</video:player_loc>
            <video:duration>
                @if($post->duration)
                    {{ $post->duration }}
                @else
                    600
                @endif
            </video:duration>
            <video:view_count>{{ $post->no_of_views }}</video:view_count>
            <video:publication_date>{{ $post->created_at }}</video:publication_date>
            <video:live>no</video:live>
        </video:video>
    @endforeach
</urlset>