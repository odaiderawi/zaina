<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\VideoRequest;
use Mezian\Zaina\Models\Category;
use Mezian\Zaina\Models\Video;

/**
 * @resource News
 *
 * News Resource Controller
 */
class VideoController extends ZainaController
{
  public $domain;

  public function __construct()
  {
    $this->domain = env( 'APP_URL' ) . '/videos';

  }

  public function index()
  {
    return Video::orderBy( 'created_at', 'DESC' )
                ->paginate( 32 );
  }

  public function show( $id )
  {
    /** @var Video $video */
    $video = Video::findOrFail( $id );
    $video->increment( 'no_of_views' );

    return $video;

  }

  public function store( VideoRequest $request )
  {
    return $request->video();
  }

  public function update( VideoRequest $request )
  {
    $this->store( $request );

    return 'video updated successfully';
  }

  public function destroy( $id )
  {
    Video::findOrFail( $id )->delete();

    return 'video deleted successfully';
  }

  public function status( $id )
  {
    /** @var Video $video */
    $video = Video::findOrFail( $id );

    $video->update( [ 'is_disabled' => ! $video->is_disabled ] );

    return 'video status set to ' . $video->is_disabled;

  }

  public function last_videos( $number )
  {
    return Video::where( 'date_to_publish', '<', Carbon::now() )
                ->orderBy( 'created_at', 'DESC' )
                ->take( $number )
                ->get();

  }

  public function search( Request $request )
  {
    $data = $request->all();

    if ( ! is_null( $data['id'] ) || $data['id'] != '' )
    {
      return Video::findOrFail( intval( $data['id'] ) );
    }

    /** @var Video $videos */
    $videos = Video::search( $data )
                   ->orderBy( 'id', 'DESC' )
                   ->paginate( 32 );

    return $videos;
  }

  public function playLists()
  {
    return Category::with( 'videos' )->playlists()->get();

  }

  public function videosByPlaylist( $id )
  {
    return Video::where( 'category_id', $id )->orderBy( 'created_at', 'DESC' )->paginate( 32 );

  }

}
