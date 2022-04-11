<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\AlbumRequest;
use Mezian\Zaina\Models\PhotoAlbum;

/**
 * @resource News
 *
 * News Resource Controller
 */
class AlbumsController extends ZainaController
{
  public $domain;

  public function __construct()
  {
    $this->domain = env( 'APP_URL' ) . '/article';
  }

  public function index()
  {
    return PhotoAlbum::orderBy( 'created_at', 'DESC' )
                     ->paginate( 32 );
  }

  public function show( $id )
  {
    /** @var PhotoAlbum $album */
    $album = PhotoAlbum::findOrFail( $id );

    return $album;

  }

  public function store( AlbumRequest $request )
  {
    return $request->album();
  }

  public function update( AlbumRequest $request )
  {
    return $this->store( $request );

  }

  public function destroy( $id )
  {
    PhotoAlbum::findOrFail( $id )->delete();

    return 'album deleted successfully';
  }

  public function status( $id )
  {
    /** @var PhotoAlbum $album */
    $album = PhotoAlbum::findOrFail( $id );

    $album->update( [ 'is_active' => ! $album->is_active ] );

    return 'album status set to ' . $album->is_disabled;

  }

  public function search( Request $request )
  {
    $data = $request->all();

    if ( ! is_null( $data['id'] ) || $data['id'] != '' )
    {
      return PhotoAlbum::where( 'id', intval( $data['id'] ) )->paginate( 1 );
    }

    /** @var PhotoAlbum $albums */
    $albums = PhotoAlbum::search( $data )
                        ->orderBy( 'id', 'DESC' )
                        ->paginate( 32 );

    return $albums;

  }

}
