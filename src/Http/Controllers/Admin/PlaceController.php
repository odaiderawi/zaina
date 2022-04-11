<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\PlaceRequest;
use Mezian\Zaina\Models\Place;
use Mezian\Zaina\Models\Place_type;
use Mezian\Zaina\Models\Placement;

/**
 * @resource Type
 *
 * Type Resource Controller
 */
class PlaceController extends ZainaController
{

  public function index()
  {
    return Place::orderBy( 'created_at', 'DESC' )->get();
  }

  public function getFreePlaces()
  {
    return Place::doesnthave( 'ad' )->orderBy( 'created_at', 'DESC' )->get()->groupBy( 'type' );
  }

  public function show( $id )
  {
    return Place::findOrFail( $id );

  }

  public function store( Request $request )
  {


    $place = new Place();

    $place->fill( $request->only( [
                                    'name',
                                    'identifier',
                                    'type',
                                    'width',
                                    'height',
                                    'active',
                                  ] ) );

    $place->save();

    return 'place saved successfully';
  }

  public function update( PlaceRequest $request, $id )
  {
    /** @var Place $place */
    $place = Place::findOrFail( $id );

    $place->fill( $request->only( [
                                    'name',
                                    'identifier',
                                    'type',
                                    'width',
                                    'height',
                                    'active',
                                  ] ) );

    $place->update();

    return 'place saved successfully';
  }

  public function places_types()
  {
    return Place_type::all();

  }

  public function destroy( $id )
  {
    Place::findOrFail( $id )->delete();

    return 'placed deleted successfully';
  }

  public function placements()
  {
    return Placement::all();

  }

}
