<?php

namespace Mezian\Zaina\Http\Controllers;

use Mezian\Zaina\Models\Article;
use Mezian\Zaina\Models\Category;
use Mezian\Zaina\Models\News;
use Mezian\Zaina\Models\Video;

class FeedController extends ZainaController
{

  public $domain;

  public function __construct()
  {
    $this->domain = env( 'APP_URL' ) . '/feed/';
  }

  public function index()
  {
    $news_categories = Category::all();

    return view( 'zaina.feed.index', compact( 'news_categories' ) );

  }

  public function news()
  {
    $lang = "ar-Ar";
    $news = News::orderBy( 'created_at', 'DESC' )->limit( 20 )->get();

    return response()->view( 'zaina.feed.news_feed',
                             compact( 'news', 'lang' ) )
                     ->header( 'Content-Type', 'text/xml' );

  }

  public function articles()
  {
    $lang = "ar-Ar";
    $news = Article::orderBy( 'created_at', 'DESC' )->limit( 20 )->get();

    return response()->view( 'zaina.feed.news_feed',
                             compact( 'news', 'lang' ) )
                     ->header( 'Content-Type', 'text/xml' );

  }

  public function news_categories( $id )
  {

    $lang = "ar-Ar";
    $news = News::where( 'category_id', $id )->orderby( 'created_at', 'DESC' )->limit( 20 )->get();

    return response()->view( 'zaina.feed.news_feed',
                             compact( 'news', 'lang' ) )
                     ->header( 'Content-Type', 'text/xml' );

  }

  public function videos()
  {
    $lang   = "ar-Ar";
    $videos = Video::orderBy( 'created_at', 'DESC' )->limit( 20 )->get();

    return response()->view( 'zaina.feed.videos_feed',
                             compact( 'videos', 'lang' ) )
                     ->header( 'Content-Type', 'text/xml' );
  }

}