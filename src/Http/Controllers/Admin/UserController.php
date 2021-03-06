<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\UserRequest;
use Mezian\Zaina\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends ZainaController
{
  public $successStatus = 200;

  public function login()
  {

    if ( Auth::attempt( [ 'email' => request( 'email' ), 'password' => request( 'password' ) ] ) )
    {
      if ( Auth::user()->is_disable != 1 )
      {
        /** @var User $user */

        $user = Auth::user();

        $success['type_id']      = implode( " ", $user->roles->pluck( 'id' )->toArray() );
        $success['access_token'] = $user->createToken( 'Zaina' )->accessToken;
        $success['image']        = $user->image;
        $success['user_id']      = $user->id;
        $success['permissions']  = $this->array2json( $user->getAllPermissions()->pluck( 'name' )->toArray() );
        $success['name']         = $user->display_name;

        return response()->json( $success, $this->successStatus );
      } else
      {
        return response()->json( [ 'error' => 'your account has disabled by your admin' ], 401 );
      }
    } else
    {
      return response()->json( [ 'error' => 'Unauthorised' ], 401 );
    }
  }

  public function register( UserRequest $request )
  {
    return $request->save();
  }

  public function update( UserRequest $request, $id )
  {
    return $request->update( $id );
  }

  public function array2json( $arr )
  {
    $data = [];
    foreach ( $arr as $key => $value )
    {
      $data[ $key ] = $value;
    }

    return $data;

  }

  public function index()
  {
    return User::orderBy( 'created_at', 'DESC' )->paginate( 32 );

  }

  public function types()
  {
    /** @var User $user */
    $user = Auth::guard( 'api' )->user();
    if ( $user->hasRole( 'super_admin' ) )
    {
      return Role::all();
    }

    return Role::query()->where( 'name', '!=', 'super_admin' )->get();

  }

  public function show( $id )
  {
    return User::query()->findOrFail( $id );

  }

  public function disable( $id )
  {
    $user       = User::query()->findOrFail( $id );
    $is_disable = $user->is_disable;

    $user->update( [ 'is_disable' => ! $is_disable ] );

    return 'success';
  }

  public function changePassword( Request $request )
  {
    $request->validate( [
                          'old_password' => 'required',
                          'new_password' => 'required|confirmed',
                        ] );

    #Match The Old Password

    if ( ! Hash::check( $request->old_password, Auth::guard( 'api' )->user()->password ) )
    {
      return response()->json( [ 'status' => 'failed', 'message' => 'Old password does not match' ], 422 );
    }

    #Update the new Password
    User::whereId( Auth::guard( 'api' )->id() )->update( [
                                                           'password' => Hash::make( $request->new_password ),
                                                         ] );

    return response()->json( [ 'status' => 'failed', 'success' => 'Password changed successfully!' ] );

  }

}
