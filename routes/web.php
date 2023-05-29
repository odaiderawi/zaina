<?php

use Illuminate\Support\Facades\Route;

Route::namespace( 'Mezian\Zaina\Http\Controllers' )->group( function () {

  Route::prefix( 'sitemap' )->group( function () {
    Route::get( '/news_sitemap_page_{page}.xml', 'SitemapController@news' );
    Route::get( '/articles_sitemap_page_{page}.xml', 'SitemapController@articles' );
    Route::get( '/videos_sitemap_page_{page}.xml', 'SitemapController@videos' );
    Route::get( '/category_sitemap.xml', 'SitemapController@categories' );
    Route::get( '/google_news_sitemap.xml', 'SitemapController@google_news' );
    Route::get( '/index_sitemap.xml', 'SitemapController@index' );

  } );

  Route::prefix( 'feed' )->name( 'feed.' )->group( function () {
    Route::get( '/', 'FeedController@index' )->name( 'index' );
    Route::get( '/news.xml', 'FeedController@news' )->name( 'news' );
    Route::get( '/articles.xml', 'FeedController@articles' )->name( 'articles' );
    Route::get( '/videos.xml', 'FeedController@videos' )->name( 'videos' );
    Route::get( '/news_category_{id}.xml', 'FeedController@news_categories' )->name( 'news-category' );
  } );
} );



