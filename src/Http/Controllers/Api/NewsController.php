<?php

namespace Mezian\Zaina\Http\Controllers\Api;

use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Models\News;
use Mezian\Zaina\Models\Setting;
use Mezian\Zaina\App\Http\Resources\Api\NewsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * @resource News
 *
 * News Resource Controller
 */
class NewsController extends ZainaController
{

  /**
   * Display a listing of the resource.
   *
   * Can get all live news with default pagination 10
   * Can get main news or special news or remaining news (every one alone , or multiple)
   *
   * * @param Request $request
   * * Without parameters return live news with 10 news in page
   *
   * * get parameters :
   * * * Integer $per_page : default 10
   * * * Boolean $main : default false (for get main news)
   * * * * Integer $main_per_page : default no_of_main_news from settings then $per_page
   * * * Boolean $special : default false (for get special news)
   * * * * Integer $special_per_page : default $per_page
   * * * Boolean $remaining : default false (for get remaining news)
   * * * * Integer $remaining_per_page : default $per_page
   * * * * Integer $remaining_main_per_page : default 4 (for remove from remaining news)
   * * * * Integer $remaining_special_per_page : default 4 (for remove from remaining news)
   * * * Boolean $events : default false (for get events)
   * * * * Integer $events_per_page : default $per_page
   * * * String $category_id (for get news by category id)
   * * * String $category_slug (alternate for category id for get news by category slug)
   * * * * Integer $category_news_per_page : default $per_page
   * * * Integer $tag_id (for get news by tag id)
   * * * String $tag_slug (alternate for tag id for get news by tag slug)
   * * * * Integer $tag_news_per_page : default $per_page
   * * * Integer $related_to_id (for get related news by news id)
   * * * String $related_to_slug (alternate for related_to_id for get related news by news slug)
   * * * * Integer related_news_per_page : default $per_page
   * * * Boolean $most_read : default false (for get events)
   * * * * Integer $most_read_per_page : default $per_page
   * * * Integer $current_news : current news id (optional to get other news)
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
   */
  public function index( Request $request )
  {
    $news     = [];
    $per_page = $request->input( 'per_page' ) ? $request->input( 'per_page' ) : 10;

    $mainCount   = Setting::where( 'key', 'no_of_main_news' )->first()->value;
    $main        = $request->input( 'main' );
    $mainPerPage = $request->input( 'main_per_page' ) ? $request->input( 'main_per_page' ) : $mainCount;

    $special        = $request->input( 'special' );
    $specialPerPage = $request->input( 'special_per_page' ) ? $request->input( 'special_per_page' ) : $per_page;

    $remaining        = $request->input( 'remaining' );
    $remainingPerPage = $request->input( 'remaining_per_page' ) ? $request->input( 'remaining_per_page' ) : $per_page;

    $events        = $request->input( 'events' );
    $eventsPerPage = $request->input( 'events_per_page' ) ? $request->input( 'events_per_page' ) : $per_page;

    $mostRead        = $request->input( 'most_read' );
    $mostReadPerPage = $request->input( 'most_read_per_page' ) ? $request->input( 'most_read_per_page' ) : $per_page;

    $currentNews         = $request->input( 'current_news' );
    $categorySlug        = $request->input( 'category_slug' );
    $categoryId          = $request->input( 'category_id' );
    $categoryNewsPerPage = $request->input( 'category_news_per_page' ) ? $request->input( 'category_news_per_page' ) : $per_page;

    $tagSlug        = $request->input( 'tag_slug' );
    $tagId          = $request->input( 'tag_id' );
    $tagNewsPerPage = $request->input( 'tag_news_per_page' ) ? $request->input( 'tag_news_per_page' ) : $per_page;

    $relatedToId        = $request->input( 'related_to_id' );
    $relatedToSlug      = $request->input( 'related_to_slug' );
    $relatedNewsPerPage = $request->input( 'related_news_per_page' ) ? $request->input( 'related_news_per_page' ) : $per_page;

    if ( $main )
    {
      $news['main'] = News::main( $currentNews )->paginate( $mainPerPage );
      $news['main']->appends( Input::only( 'per_page', 'main', 'current_news', 'main_per_page' ) );
    }
    if ( $special )
    {
      $news['special'] = News::special()->paginate( $specialPerPage );
      $news['special']->appends( Input::only( 'per_page', 'special', 'special_per_page' ) );
    }
    if ( $remaining )
    {
      $news['remaining'] = News::getRemaining( $request->input( 'remaining_main_per_page' ), $request->input( 'remaining_special_per_page' ) )->paginate( $remainingPerPage );
      $news['remaining']->appends( Input::only( 'per_page', 'remaining', 'remaining_main_per_page', 'remaining_special_per_page', 'remaining_per_page' ) );
    }

    if ( $events )
    {
      $news['events'] = News::events()->paginate( $eventsPerPage );
      $news['events']->appends( Input::only( 'per_page', 'events', 'events_per_page' ) );
    }
    if ( $mostRead )
    {
      $news['most_read'] = News::mostRead( $currentNews )->paginate( $mostReadPerPage );
      $news['most_read']->appends( Input::only( 'per_page', 'most_read', 'current_news', 'most_read_per_page' ) );
    }

    if ( $categorySlug )
    {
      $news['by_category'] = News::getByCategorySlug( $categorySlug )->paginate( $categoryNewsPerPage );
      $news['by_category']->appends( Input::only( 'per_page', 'by_category', 'category_slug', 'category_news_per_page' ) );
    } else if ( $categoryId )
    {
      $news['by_category'] = News::getByCategory( $categoryId )->paginate( $categoryNewsPerPage );
      $news['by_category']->appends( Input::only( 'per_page', 'by_category', 'category_id', 'category_news_per_page' ) );
    }

    if ( $tagSlug )
    {
      $news['by_tag'] = News::getByTagSlug( $tagSlug )->paginate( $tagNewsPerPage );
      $news['by_tag']->appends( Input::only( 'per_page', 'by_tag', 'tag_slug', 'tag_news_per_page' ) );
    } else if ( $tagId )
    {
      $news['by_tag'] = News::getByTag( $tagId )->paginate( $tagNewsPerPage );
      $news['by_tag']->appends( Input::only( 'per_page', 'by_tag', 'tag_id', 'tag_news_per_page' ) );
    }

    if ( $relatedToId )
    {
      $relatedToNews = News::find( $relatedToId );
      if ( ! $relatedToNews )
      {
        return $this->makeErrorResponse( $request, 404, 'Not Found !' );
      }
      $news['related'] = $relatedToNews->getRelated( News::class )->paginate( $relatedNewsPerPage );
      $news['related']->appends( Input::only( 'per_page', 'related_to_id', 'related_news_per_page' ) );
    } else if ( $relatedToSlug )
    {
      $relatedToNews = News::where( 'slug', $relatedToSlug )->first();
      if ( ! $relatedToNews )
      {
        return $this->makeErrorResponse( $request, 404, 'Not Found !' );
      }
      $news['related'] = $relatedToNews->getRelated( News::class )->paginate( $relatedNewsPerPage );
      $news['related']->appends( Input::only( 'per_page', 'related_to_slug', 'related_news_per_page' ) );
    }

    if ( count( $news ) == 0 )
    {
      $news = News::live()->paginate( $per_page );
      $news->appends( Input::only( 'per_page' ) );
    }

    return NewsResource::collection( $news );

  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   *
   * @return \Illuminate\Http\Response
   */

  public function show( Request $request, $id )
  {

    $news = News::where( 'id', $id )
                ->live()
                ->first();

    if ( $news )
    {
      return new NewsResource( $news );
    } else
    {
      return $this->makeErrorResponse( $request, 404, 'Not Found !' );
    }
  }

}
