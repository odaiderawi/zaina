<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\LiveBroadcastRequest;
use Mezian\Zaina\Models\LiveBroadcast;

/**
 * @resource Type
 *
 * Type Resource Controller
 */
class LiveBroadcastController extends ZainaController
{

  public function index()
  {
    return LiveBroadcast::orderBy( 'created_at', 'DESC' )->paginate( 32 );
  }

  public function store( LiveBroadcastRequest $request )
  {
    return $request->save();
  }

  public function update( LiveBroadcastRequest $request, $id )
  {
    return $this->store( $request );
  }

  public function destroy( $id )
  {
    LiveBroadcast::findOrFail( $id )->delete();

    return response()->json( [ 'success' => 'live broadcast deleted successfully' ], 200 );
  }

  public function status( $id )
  {
    /** @var LiveBroadcast $live */
    $live = LiveBroadcast::findOrFail( $id );

    $live->update( [ 'is_active' => ! $live->is_active ] );

    return response()->json( [ 'success' => 'live broadcast status changed successfully' ], 200 );

  }

  public function show( $id )
  {
    return LiveBroadcast::findOrFail( $id );

  }

}
