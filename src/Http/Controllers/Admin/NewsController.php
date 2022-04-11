<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\NewsRequest;
use Mezian\Zaina\Models\News;

/**
 * @resource News
 *
 * News Resource Controller
 */
class NewsController extends ZainaController
{
  public $domain;

  public function __construct()
  {
    $this->domain = env( 'APP_URL' ) . '/news';

  }

  public function index()
  {
    return News::orderBy( 'id', 'DESC' )
               ->paginate( 32 );
  }

  public function show( $id )
  {
    $news = News::with( [ 'photos' ] )->findOrFail( $id );

    return $news;

  }

  public function draft( NewsRequest $request )
  {
//        dd($request);
    return $request->news();
  }

  public function store( NewsRequest $request )
  {
    $this->draft( $request )->update( [ 'is_draft' => 0 ] );
  }

  public function update( NewsRequest $request )
  {
    $this->draft( $request )->update( [ 'is_draft' => 0 ] );

  }

  public function destroy( $id )
  {
    News::findOrFail( $id )->delete();

    return 'news deleted successfully';
  }

  public function status( $id )
  {

    $news = News::withoutEvents( function () use ( $id ) {
      $news              = News::find( $id );
      $news->is_disabled = ! $news->is_disabled;
      $news->save();

      return 'news status set to ' . $news->is_disabled;
    } );

  }

  public function last_news( $number )
  {
    return News::where( 'date_to_publish', '<', Carbon::now() )
               ->where( 'is_draft', 0 )
               ->orderBy( 'created_at', 'DESC' )
               ->take( $number )
               ->get();

  }

  public function search( Request $request )
  {
    $data = $request->all();

    if ( ! is_null( $data['id'] ) || $data['id'] != '' )
    {
      return News::where( 'id', intval( $data['id'] ) )->paginate( 1 );
    }

    /** @var News $news */
    $news = News::search( $data )
                ->orderBy( 'id', 'DESC' )
                ->paginate( 32 );

    return $news;

  }

  public function deleted()
  {
    return News::onlyTrashed()->orderBy( 'id', 'DESC' )->paginate( 32 );

  }

  public function restore( $id )
  {
    News::withTrashed()->findOrFail( $id )->restore();

    return 'news restored successfully';
  }

}
