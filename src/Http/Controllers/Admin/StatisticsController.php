<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Models\News;
use Mezian\Zaina\Models\User;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;

/**
 * @resource News
 *
 * News Resource Controller
 */
class StatisticsController extends ZainaController
{
  public function editors( $period )
  {

    if ( $period == 'day' )
    {
      $editors = User::join( 'news', 'news.created_by', '=', 'users.id' )
                     ->whereDate( 'news.created_at', '=', Carbon::today() )
                     ->groupBy( 'users.id' )
                     ->get( [ 'users.*', DB::raw( 'COUNT(users.id) as count' ) ] );
    } else if ( $period == 'yesterday' )
    {
      $editors = User::join( 'news', 'news.created_by', '=', 'users.id' )
                     ->whereDate( 'news.created_at', '=', Carbon::yesterday() )
                     ->groupBy( 'users.id' )
                     ->get( [ 'users.*', DB::raw( 'COUNT(users.id) as count' ) ] );
    } else if ( $period == 'week' )
    {
      $editors = User::join( 'news', 'news.created_by', '=', 'users.id' )
                     ->whereDate( 'news.created_at', '>', Carbon::now()->subWeek() )
                     ->groupBy( 'users.id' )
                     ->get( [ 'users.*', DB::raw( 'COUNT(users.id) as count' ) ] );
    } else if ( $period == 'month' )
    {
      $editors = User::join( 'news', 'news.created_by', '=', 'users.id' )
                     ->whereDate( 'news.created_at', '>', Carbon::now()->startOfMonth() )
                     ->groupBy( 'users.id' )
                     ->get( [ 'users.*', DB::raw( 'COUNT(users.id) as count' ) ] );
    } else if ( $period == 'last_month' )
    {
      $editors = User::join( 'news', 'news.created_by', '=', 'users.id' )
                     ->whereDate( 'news.created_at', '>', Carbon::now()->subMonth() )
                     ->groupBy( 'users.id' )
                     ->get( [ 'users.*', DB::raw( 'COUNT(users.id) as count' ) ] );
    }

    $collect = collect( $editors );
    $editors = $collect->sortByDesc( 'count' );

    return $editors->values()->all();

  }

  public function statistics()
  {

    $total = Analytics::fetchTotalVisitorsAndPageViews( Period::days( 1 ) );

    $analytics = Analytics::getAnalyticsService();

    $activeNow = $analytics->data_realtime->get(
      'ga:249173410',
      'rt:activeUsers' );

    $visitors  = count( $total ) ? $total[1]['visitors'] : 0;
    $pageViews = count( $total ) ? $total[1]['pageViews'] : 0;
    $activeNow = $activeNow->rows[0][0];

    $published_news = News::whereDate( 'created_at', Carbon::today() )->count();

    return response()->json( [
                               'pageViews'      => $pageViews,
                               'sessions'       => $visitors,
                               'active_users'   => $activeNow,
                               'published_news' => $published_news,
                             ] );

  }

  public function fetchMostVisitedPages( $period )
  {

    if ( $period == 'day' )
    {
      $pages = Analytics::fetchMostVisitedPages( Period::days( 0 ), 5 );

    } else if ( $period == 'yesterday' )
    {
      $pages = Analytics::fetchMostVisitedPages( Period::days( 1 ), 5 );
    } else if ( $period == 'week' )
    {
      $pages = Analytics::fetchMostVisitedPages( Period::days( 7 ), 5 );
    } else if ( $period == 'month' )
    {
      $pages = Analytics::fetchMostVisitedPages( Period::months( 0 ), 5 );
    } else if ( $period == 'last_month' )
    {
      $pages = Analytics::fetchMostVisitedPages( Period::months( 1 ), 5 );
    }

    return $pages;

  }
}
