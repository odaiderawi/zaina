<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Mezian\Zaina\Http\Requests\TagRequest;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Models\Tag;

/**
 * @resource Type
 *
 * Type Resource Controller
 */
class TagController extends ZainaController
{

  public function index()
  {
    return Tag::orderBy( 'no_of_uses', 'DESC' )->paginate( 32 );
  }

  public function store( TagRequest $request )
  {
    return $request->tag();
  }

  public function update( TagRequest $request, $id )
  {
    return $this->store( $request );
  }

  public function show( $id )
  {
    return Tag::findOrFail( $id );

  }

  public function destroy( $id )
  {
    Tag::findOrFail( $id )->delete();

    return response()->json( [ 'success' => 'tag deleted successfully' ], 200 );
  }

  public function status( $id )
  {
    /** @var Tag $tag */
    $tag = Tag::findOrFail( $id );

    $tag->update( [ 'is_disabled' => ! $tag->is_disabled ] );

    return response()->json( [ 'success' => 'tag status changed successfully' ], 200 );

  }

  public function search_tags( $id )
  {
    return Tag::where( 'name', 'LIKE', '%' . $id . '%' )->select( [ 'id', 'name' ] )->get();

  }

  public function search( Request $request )
  {
    $data = $request->all();

    if ( ! is_null( $data['id'] ) || $data['id'] != '' )
    {
      return Tag::where( 'id', intval( $data['id'] ) )->paginate( 1 );
    }

    /** @var News $news */
    $tags = Tag::search( $data )
               ->orderBy( 'id', 'DESC' )
               ->paginate( 32 );

    return $tags;

  }

}
