<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">


    @foreach($news as $post)
        <url>
            <loc> {{ $post->url }} </loc>
            <news:news>
                <news:publication>
                    <news:name>{{ env('APP_NAME') }}</news:name>
                    <news:language>ar</news:language>
                </news:publication>

                <news:publication_date>{{ $post->date_to_publish }}</news:publication_date>
                <news:title>{{ $post->title  }}</news:title>
                {{--<news:keywords>{{getKeywords($res->id)}}</news:keywords>--}}
            </news:news>
            <image:image>
                <image:loc>{{ env('APP_URL') . '/' . $post->image  }} </image:loc>
                <image:title>{{ $post->title }}</image:title>
                <image:caption>{{ $post->title }}</image:caption>
            </image:image>
        </url>
    @endforeach
</urlset>