<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Models\Author;

/**
 * @resource Type
 *
 * Type Resource Controller
 */
class AuthorController extends ZainaController
{

  public function index()
  {

  }

  public function store( Request $request )
  {

  }

  public function update( Request $request, $id )
  {

  }

  public function show( $id )
  {


  }

  public function destroy( $id )
  {

  }

  public function status( $id )
  {


  }

  public function search_authors( $id )
  {
    return Author::where( 'name', 'LIKE', '%' . $id . '%' )->select( [ 'id', 'name' ] )->get();

  }

}
