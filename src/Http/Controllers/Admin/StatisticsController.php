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

  public function getRealTimeUsers()
  {
    $analytics = Analytics::getAnalyticsService();

    $activeNow = $analytics->data_realtime->get(
      'ga:' . settings( 'google_view_id' ),
      'rt:activeUsers' );

    $activeNow = count( $activeNow->rows ?? [] ) ? $activeNow->rows[0][0] : 0;

    return response()->json( [
                               'active_users' => $activeNow,
                             ] );

  }

  public function statistics( $time = 'day' )
  {

    $period   = $this->getPeriodInDays( $time );
    $carbon   = $this->getCarbonInDays( $time )[0];
    $operator = $this->getCarbonInDays( $time )[1];

    $total = Analytics::fetchTotalVisitorsAndPageViews( $period );

    $res = Analytics::performQuery( $period, 'ga:sessions', [
      'dimensions' => 'ga:operatingSystem',
    ] );

    $operation_system = collect( $res['rows'] ?? [] )->map( function ( array $browserRow ) {
      return [
        'operation_system' => $browserRow[0],
        'sessions'         => (int) $browserRow[1] ?? 0,
      ];
    } );

    $visitors  = $total[1]['visitors'] ?? 0;
    $pageViews = $total[1]['pageViews'] ?? 0;

    $published_news = News::whereDate( 'created_at', $operator, $carbon )->count();

    $userTypes = Analytics::fetchUserTypes( $period );
    $browsers  = Analytics::fetchTopBrowsers( $period );
    $referrers = Analytics::fetchTopReferrers( $period );

    return response()->json( [
                               'pageViews'        => $pageViews,
                               'sessions'         => $visitors,
                               'published_news'   => $published_news,
                               'types'            => $userTypes,
                               'browsers'         => $browsers,
                               'referrers'        => $referrers,
                               'operation_system' => $operation_system,
                             ] );

  }

  private function getPeriodInDays( $time ): Period
  {
    if ( $time == 'day' )
    {
      $period = Period::days( 1 );

    } else if ( $time == 'yesterday' )
    {
      $period = Period::days( 2 );
    } else if ( $time == 'week' )
    {
      $period = Period::days( 7 );
    } else if ( $time == 'month' )
    {
      $period = Period::months( 0 );
    } else
    {
      $period = Period::months( 1 );
    }

    return $period;
  }

  private function getCarbonInDays( $time ): array
  {
    if ( $time == 'day' )
    {
      $period   = Carbon::today();
      $operator = '=';

    } else if ( $time == 'yesterday' )
    {
      $period   = Carbon::yesterday();
      $operator = '=';
    } else if ( $time == 'week' )
    {
      $period   = Carbon::now()->subWeek();
      $operator = '>';
    } else if ( $time == 'month' )
    {
      $period   = Carbon::now()->startOfMonth();
      $operator = '>';
    } else
    {
      $period   = Carbon::now()->subMonth();
      $operator = '>';
    }

    return [ $period, $operator ];
  }

  public function fetchMostVisitedPages( $period )
  {

    if ( $period == 'day' )
    {
      $pages = Analytics::fetchMostVisitedPages( Period::days( 0 ), 9 );

    } else if ( $period == 'yesterday' )
    {
      $pages = Analytics::fetchMostVisitedPages( Period::days( 1 ), 9 );
    } else if ( $period == 'week' )
    {
      $pages = Analytics::fetchMostVisitedPages( Period::days( 7 ), 9 );
    } else if ( $period == 'month' )
    {
      $pages = Analytics::fetchMostVisitedPages( Period::months( 0 ), 9 );
    } else
    {
      $pages = Analytics::fetchMostVisitedPages( Period::months( 1 ), 9 );
    }

    $data = [];

    foreach ( $pages as $index => $page )
    {
      $data[ $index ]['url']       = config( 'app.url' ) . $page['url'];
      $data[ $index ]['pageTitle'] = $page['pageTitle'];
      $data[ $index ]['pageViews'] = $page['pageViews'];
    }

    return $data;

  }
}
