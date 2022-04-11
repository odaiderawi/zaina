<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\CategoryRequest;
use Mezian\Zaina\Models\Category;

/**
 * @resource Type
 *
 * Type Resource Controller
 */
class CategoryController extends ZainaController
{

  public function index()
  {
    return Category::where( 'parent_id', null )->get();
  }

  public function getCategories()
  {
    return Category::with( 'parent' )->get();

  }

  public function store( CategoryRequest $request )
  {
    return $request->category();
  }

  public function update( CategoryRequest $request, $id )
  {
    return $this->store( $request );
  }

  public function destroy( $id )
  {
    Category::findOrFail( $id )->delete();

    return response()->json( [ 'success' => 'category deleted successfully' ], 200 );
  }

  public function status( $id )
  {
    /** @var Category $category */
    $category = Category::findOrFail( $id );

    $category->update( [ 'is_active' => ! $category->is_active ] );

    return response()->json( [ 'success' => 'category status changed successfully' ], 200 );

  }

  public function show( $id )
  {
    return Category::findOrFail( $id );

  }

  public function parents()
  {
    $categories = Category::where( 'parent_id', null )->get()->toArray();

    return response()->json( $categories, 200 );

  }

  public function videos( $id )
  {


  }

  public function getAvailableSorts()
  {
    $categories_count = Category::query()->count();

    return range( 1, $categories_count );

  }

}
