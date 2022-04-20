<?php

namespace Mezian\Zaina\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Mezian\Zaina\Models\User;
use Spatie\Permission\Models\Role;

class UserRequest extends FormRequest
{

  public $successStatus = 200;

  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    switch ( $this->method() )
    {
      case 'GET':
      case 'DELETE':
      {
        return [];
      }
      case 'POST':
      {
        return [
          'username'     => 'required|unique:users',
          'email'        => 'required|email|unique:users',
          'first_name'   => 'required|string|min:3|max:30',
          'last_name'    => 'required|string|min:3|max:30',
          'display_name' => 'required|string|min:3|max:30',
          'mobile'       => 'nullable|min:5|max:30',
          'facebook'     => 'nullable|string|min:5|max:200',
          'twitter'      => 'nullable|string|min:5|max:200',
          'image'        => 'nullable|string',
          'description'  => 'nullable|string|min:5',
          'address'      => 'nullable|string|min:3',
          'is_disable'   => 'nullable|boolean',
        ];
      }
      case 'PUT':
      case 'PATCH':
      {
        return [
          'username'     => 'required|unique:users,username,' . $this->id,
          'email'        => 'required|email|unique:users,email,' . $this->id,
          'first_name'   => 'required|string|min:3|max:30',
          'last_name'    => 'required|string|min:3|max:30',
          'display_name' => 'required|string|min:3|max:30',
          'mobile'       => 'nullable|min:5|max:30',
          'facebook'     => 'nullable|min:5|max:200',
          'twitter'      => 'nullable|string|min:5|max:200',
          'image'        => 'nullable|string',
          'description'  => 'nullable|string|min:5',
          'address'      => 'nullable|string|min:3',
          'is_disable'   => 'nullable|boolean',
        ];
      }
      default:
        break;
    }
  }

  public function save()
  {
    $input               = request()->all();
    $input['password']   = bcrypt( $input['password'] );
    $user                = User::create( $input );
    $success['email']    = $user->email;
    $success['username'] = $user->username;

    $role = request( 'type_id' );
    if ( isset( $role ) )
    {

      $role_r = Role::where( 'id', '=', $role )->firstOrFail();
      $user->assignRole( $role_r );
    }

    return response()->json( [ 'success' => $success ], $this->successStatus );

  }

  public function update( $id )
  {
    $input = request()->all();
//        $input['password'] = bcrypt($input['password']);
    $user = User::findOrFail( $id );

    $user->update( $input );

    $success['email']    = $user->email;
    $success['username'] = $user->username;

    $roles = $user->getRoleNames();
    foreach ( $roles as $rol )
    {
      $user->removeRole( $rol );
    }
    $role = request( 'type_id' );
    if ( isset( $role ) )
    {
      $role_r = Role::where( 'id', '=', $role )->firstOrFail();
      $user->assignRole( $role_r );
    }

    return response()->json( [ 'success' => $success ], $this->successStatus );

  }

}
