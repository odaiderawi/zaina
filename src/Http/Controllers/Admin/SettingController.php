<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\SettingRequest;
use Mezian\Zaina\Models\BreakingNews;
use Mezian\Zaina\Models\Setting;

/**
 * @resource News
 *
 * News Resource Controller
 */
class SettingController extends ZainaController
{
  public function __construct()
  {
    $this->middleware( 'auth', [ 'except' => [ 'index' ] ] );
  }

  public function index()
  {
    return Setting::pluck( 'value', 'key' )->toJson();
  }

  public function update( SettingRequest $request )
  {
    return $request->update();

  }

  public function destroy( $id )
  {
    BreakingNews::findOrFail( $id )->delete();

    return response()->json( 'breaking news deleted successfully', 200 );
  }

  public function status( $id )
  {
    /** @var BreakingNews $news */
    $news = BreakingNews::findOrFail( $id );

    $news->update( [ 'is_active' => ! $news->is_active ] );

    return 'news status set to ' . $news->is_disabled;

  }

  public function search( Request $request )
  {
    $data = $request->all();

    if ( ! is_null( $data['id'] ) || $data['id'] != '' )
    {
      return BreakingNews::findOrFail( intval( $data['id'] ) );
    }

    /** @var BreakingNews $news */
    $news = BreakingNews::search( $data )
                        ->orderBy( 'id', 'DESC' )
                        ->paginate( 32 );

    return $news;

  }

}
