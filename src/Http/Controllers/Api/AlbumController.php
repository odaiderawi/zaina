<?php
/**
 * Created by PhpStorm.
 * User: odai
 * Date: 7/2/2019
 * Time: 9:26 PM
 */

namespace Mezian\Zaina\Http\Controllers\Api;

use Mezian\Zaina\App\Http\Controllers\ZainaController;
use Mezian\Zaina\Models\PhotoAlbum;
use Illuminate\Http\Request;

class AlbumController extends ZainaController
{

  /**
   * Display a listing of all news with filters.
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
    $albums   = [];
    $per_page = $request->input( 'per_page' ) ? $request->input( 'per_page' ) : 10;

//        $mainCount = Setting::where('key','no_of_main_news')->first()->value;
    $main        = $request->input( 'main' );
    $mainPerPage = $request->input( 'main_per_page' ) ? $request->input( 'main_per_page' ) : $per_page;

    $mostRead        = $request->input( 'most_read' );
    $mostReadPerPage = $request->input( 'most_read_per_page' ) ? $request->input( 'most_read_per_page' ) : $per_page;

    $relatedToId          = $request->input( 'related_to_id' );
    $relatedToSlug        = $request->input( 'related_to_slug' );
    $relatedVideosPerPage = $request->input( 'related_videos_per_page' ) ? $request->input( 'related_videos_per_page' ) : $per_page;

    if ( $main )
    {
      $albums['main'] = PhotoAlbum::main()->paginate( $mainPerPage );
      $albums['main']->appends( Request::only( 'per_page', 'main', 'main_per_page' ) );
    }

    if ( $mostRead )
    {
      $albums['most_read'] = PhotoAlbum::mostRead( $currentNews )->paginate( $mostReadPerPage );
      $albums['most_read']->appends( Input::only( 'per_page', 'most_read', 'most_read_per_page' ) );
    }

    if ( $relatedToId )
    {
      $relatedToVideos = PhotoAlbum::find( $relatedToId );
      if ( ! $relatedToVideos )
      {
        return $this->makeErrorResponse( $request, 404, 'Not Found !' );
      }
      $albums['related'] = $relatedToVideos->getRelated( Video::class )->paginate( $relatedVideosPerPage );
      $albums['related']->appends( Input::only( 'per_page', 'related_to_id', 'related_news_per_page' ) );
    } else if ( $relatedToSlug )
    {
      $relatedToVideos = PhotoAlbum::where( 'slug', $relatedToSlug )->first();
      if ( ! $relatedToVideos )
      {
        return $this->makeErrorResponse( $request, 404, 'Not Found !' );
      }
      $albums['related'] = $relatedToVideos->getRelated( Video::class )->paginate( $relatedVideosPerPage );
      $albums['related']->appends( Input::only( 'per_page', 'related_to_slug', 'related_videos_per_page' ) );
    }

    if ( count( $albums ) == 0 )
    {
      $albums = PhotoAlbum::live()->paginate( $per_page );
      $albums->appends( Input::only( 'per_page' ) );
    }

    return collect( $albums );

  }

  /**
   * Display the single album.
   *
   * @param int $id
   *
   * @return \Illuminate\Http\Response
   */

  public function show( $id )
  {

    $album = PhotoAlbum::where( 'id', $id )
                       ->first();

    if ( $album )
    {
      return $album;
    }
  }

}