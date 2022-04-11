<?php
/**
 * Created by PhpStorm.
 * User: odai
 * Date: 7/5/2019
 * Time: 4:40 PM
 */

namespace Mezian\Zaina\Http\Controllers\Api;

use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Models\Category;

class CategoryController extends ZainaController
{

  public function index()
  {
    $father = request( 'father' );
    if ( $father === 'news' )
    {
      return Category::where( 'parent_id', 1 )->get();
    } else if ( $father === 'videos' )
    {
      return Category::where( 'parent_id', 555 )->get();
    } else if ( $father === 'sports' )
    {
      return Category::where( 'parent_id', 2 )->get();
    } else
    {
      return Category::all();
    }

  }

}