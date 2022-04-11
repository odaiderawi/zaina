<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Models\Type;

/**
 * @resource Type
 *
 * Type Resource Controller
 */
class TypeController extends ZainaController
{

  public function index()
  {
    return Type::all( [ 'id', 'name' ] );
  }

}
