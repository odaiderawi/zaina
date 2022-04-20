<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Models\Page;

class PageController extends ZainaController
{

  public function index()
  {
    return Page::query()->get();
  }

  public function show( $id )
  {
    return Page::query()->findOrFail( $id );
  }

  public function store( Request $request )
  {
    $request->validate( [
                          'name'        => 'required|string',
                          'description' => 'nullable|string',
                          'content'     => 'required|string',
                          'is_active'   => 'boolean',
                          'is_draft'    => 'boolean',
                        ] );

    $page = new Page();

    $page->fill( $request->only( [
                                   'name',
                                   'description',
                                   'content',
                                   'is_active',
                                   'is_draft',
                                 ] ) );

    $page->created_by = Auth::guard( 'api' )->id();

    $page->save();

    return 'success';

  }

  public function update( Request $request, $id )
  {
    $request->validate( [
                          'name'        => 'required|string',
                          'description' => 'nullable|string',
                          'content'     => 'required|string',
                          'is_active'   => 'boolean',
                          'is_draft'    => 'boolean',
                        ] );

    $page = Page::query()->findOrFail( $id );

    $page->fill( $request->only( [
                                   'name',
                                   'description',
                                   'content',
                                   'is_active',
                                   'is_draft',
                                 ] ) );

    $page->modified_by = Auth::guard( 'api' )->id();

    $page->update();

    return 'success';
  }

  public function status( $id )
  {
    $page = Page::query()->findOrFail( $id );
    $page->update( [ 'is_active' => ! $page->is_active ] );

    return 'success';
  }

}