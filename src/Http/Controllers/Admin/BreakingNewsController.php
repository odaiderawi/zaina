<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\BreakingNewsRequest;
use Mezian\Zaina\Models\BreakingNews;

/**
 * @resource News
 *
 * News Resource Controller
 */
class BreakingNewsController extends ZainaController
{
  public function index()
  {
    return BreakingNews::orderBy( 'created_at', 'DESC' )
                       ->paginate( 32 );
  }

  public function show( $id )
  {
    /** @var BreakingNews $news */
    $news = BreakingNews::findOrFail( $id );

    return $news;

  }

  public function store( BreakingNewsRequest $request )
  {
    return $request->save();

  }

  public function update( BreakingNewsRequest $request )
  {
    $this->store( $request );

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
