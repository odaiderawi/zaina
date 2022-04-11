<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
    <channel>
        <title> {{ env('APP_NAME') }}</title>
        <link>{{ env('APP_URL') }}</link>
        <description> {{ env('APP_NAME') }}</description>
        <language>{{$lang}}</language>

        @foreach($news as $page)
            <item>
                <title>{{ $page->title }}</title>
                <description>{{ $page->description }}</description>

                <image>
                    <url>{{ env('APP_URL') . '/' . zaina_image($page->image) }}</url>
                    <link>{{ $page->url }}</link>
                </image>

                <link>{{ $page->url }}</link>
                <pubDate>{{ $page->created_at }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>