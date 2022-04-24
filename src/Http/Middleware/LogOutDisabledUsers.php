<?php

namespace Mezian\Zaina\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class LogOutDisabledUsers
{
  /**
   * Handle an incoming request.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Closure $next
   *
   * @return mixed
   */
  public function handle( Request $request, Closure $next )
  {

    if ( auth()->guard( 'api' )->check() && ( auth()->guard( 'api' )->user()->is_disable == 1 ) )
    {
      Auth::guard( 'api' )->logout();

      abort( 403 );

    }

    return $next( $request );
  }
}
