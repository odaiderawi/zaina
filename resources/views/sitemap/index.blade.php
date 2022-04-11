<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
              xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd">

    @for($i = 1; $i < $news_pages_number ; $i++)
        <sitemap>
            <loc>{{$domain."news_sitemap_page_".$i.".xml"}}</loc>
        </sitemap>
    @endfor

    @for($i = 1; $i < $articles_pages_number ; $i++)
        <sitemap>
            <loc>{{$domain."articles_sitemap_page_".$i.".xml"}}</loc>
        </sitemap>
    @endfor

    @for($i = 1; $i < $videos_pages_number ; $i++)
        <sitemap>
            <loc>{{$domain."videos_sitemap_page_".$i.".xml"}}</loc>
        </sitemap>
    @endfor


    <sitemap>
        <loc>{{$domain.'google_news_sitemap.xml'}}</loc>
    </sitemap>

    <sitemap>
        <loc>{{$domain.'category_sitemap.xml'}}</loc>
    </sitemap>

    <sitemap>
        <loc>{{$domain.'sitemap/pages_sitemap.xml'}}</loc>
    </sitemap>

</sitemapindex>