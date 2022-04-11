<?php
/**
 * Created by PhpStorm.
 * User: odai
 * Date: 7/27/2019
 * Time: 2:17 PM
 */

namespace Mezian\Zaina\Http\Controllers\Admin;

use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\AdRequest;
use Mezian\Zaina\Models\Ad;

class AdController extends ZainaController
{

  public function index()
  {
    return Ad::orderBy( 'created_at', 'desc' )->with( 'place' )->get();

  }

  public function show( $id )
  {
    $ad = Ad::with( 'place' )->findOrFail( $id );

    return $ad;
  }

  public function store( AdRequest $request )
  {
    return $request->ad();
  }

  public function update( AdRequest $request )
  {
    return $this->store( $request );

  }

  public function destroy( $id )
  {
    Ad::findOrFail( $id )->delete();

    return 'Ad deleted successfully';
  }

}