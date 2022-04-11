<?php

namespace Mezian\Zaina\Http\Controllers;

use Mezian\Zaina\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ZainaController extends Controller
{
  protected function makeResponse( $request, $template, $data = [] )
  {
    if ( $request->ajax() || $request->isJson() || $request->wantsJson() )
    {
      return response()->json( $data );
    }

    return view( $template, $data );
  }

  protected function makeErrorResponse( $request, $code = 400, $message = null )
  {
    if ( $request->ajax() || $request->isJson() || $request->wantsJson() )
    {
      return $this->apiErrorResponse( $code, $message );
    }

    return abort( $code, $message );
  }

  private function apiErrorResponse( $code = 400, $message = null )
  {
    // check if $message is object and transforms it into an array
    if ( is_object( $message ) )
    {
      $message = $message->toArray();
    }

    switch ( $code )
    {
      default:
        $code_message = 'error_occured';
        break;
    }

    $data = [
      'code'    => $code,
      'message' => $code_message,
      'data'    => $message,
    ];

    // return an error
    return response()->json( $data, $code );
  }
}
