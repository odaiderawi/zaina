<?php

namespace Mezian\Zaina\Http\Controllers;

use Mezian\Zaina\Models\Article;
use Mezian\Zaina\Models\Category;
use Mezian\Zaina\Models\News;
use Mezian\Zaina\Models\Video;

class SitemapController extends ZainaController
{

  public $domain;

  public function __construct()
  {
    $this->domain = env( 'APP_URL' ) . '/sitemap/';
  }

  public function news( $page )
  {
    $time     = 'yearly';
    $priority = 0.9;

    if ( $page > 0 )
    {
      $offsetno = ( $page - 1 ) * 5000;
      $news     = News::limit( 5000 )->offset( $offsetno )->get();
    }

    return response()->view( 'zaina.sitemap.news',
                             compact( 'news', 'time', 'priority' ) )
                     ->header( 'Content-Type', 'text/xml' );

  }

  public function articles( $page )
  {
    $time     = 'yearly';
    $priority = 0.9;

    if ( $page > 0 )
    {
      $offsetno = ( $page - 1 ) * 5000;
      $articles = Article::limit( 5000 )->offset( $offsetno )->get();
    }

    return response()->view( 'zaina.sitemap.articles',
                             compact( 'articles', 'time', 'priority' ) )
                     ->header( 'Content-Type', 'text/xml' );

  }

  public function categories()
  {
    $categories = Category::all();

    return response()->view( 'zaina.sitemap.categories', [
      'categories' => $categories,
    ] )->header( 'Content-Type', 'text/xml' );

  }

  public function google_news()
  {
    $time     = 'yearly';
    $priority = 0.9;

    $news = News::limit( 1000 )->orderBy( 'created_at', 'DESC' )->get();

    return response()->view( 'zaina.sitemap.google_news',
                             compact( 'news', 'time', 'priority' ) )
                     ->header( 'Content-Type', 'text/xml' );
  }

  public function videos( $page )
  {
    $time     = 'yearly';
    $priority = 0.9;

    if ( $page > 0 )
    {
      $offsetno = ( $page - 1 ) * 5000;
      $videos   = Video::limit( 5000 )->offset( $offsetno )->get();
    }

    return response()->view( 'zaina.sitemap.videos',
                             compact( 'videos', 'time', 'priority' ) )
                     ->header( 'Content-Type', 'text/xml' );
  }

  public function index()
  {
    $domain = $this->domain;

    $time     = 'always';
    $priority = 0.1;

    $news              = News::count();
    $news_pages_number = $news / 5000;
    $news_pages_number += 1;

    $articles              = Article::count();
    $articles_pages_number = $articles / 5000;
    $articles_pages_number += 1;

    $videos              = Video::count();
    $videos_pages_number = $videos / 5000;
    $videos_pages_number += 1;

    return response()->view( 'zaina.sitemap.index',
                             compact( 'domain', 'news_pages_number', 'articles_pages_number', 'videos_pages_number', 'time', 'priority' ) )
                     ->header( 'Content-Type', 'text/xml' );
  }

}