<?php

namespace Mezian\Zaina\Traits;

use Illuminate\Support\Facades\Validator;

trait ApiResponseTrait
{

  public $paginateNumber = 10;

  /*
   *[
   * 'data' =>
   * 'status' => true , false
   * 'error' => ''
  */

  public function apiResponse( $data = null, $error = null, $code = 200 )
  {

    $array = [
      'data'   => $data,
      'status' => in_array( $code, $this->successCode() ) ? true : false,
      'error'  => $error,
    ];

    return response( $array, $code );

  }

  public function successCode()
  {
    return [
      200,
      201,
      202,
    ];
  }

  public function notFoundResponse()
  {
    return $this->apiResponse( null, 'not Found !', 404 );
  }

  public function apiValidation( $request, $array )
  {
    //      Laravel Validation method
    $validate = Validator::make( $request->all(), $array );

    if ( $validate->fails() )
    {
      return $this->apiResponse( null, $validate->errors(), 422 );
    }

  }

  public function unKnownError()
  {
    return $this->apiResponse( null, 'un known error', 520 );
  }

  public function createdResponse( $data )
  {
    return $this->apiResponse( $data, null, 201 );
  }

  public function deleteResponse()
  {
    return $this->apiResponse( true, null, 200 );
  }

  public function returnSuccessPost( $post )
  {
    return $this->apiResponse( $post );
  }

}
