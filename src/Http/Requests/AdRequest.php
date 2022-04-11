<?php
/**
 * Created by PhpStorm.
 * User: odai
 * Date: 7/27/2019
 * Time: 2:19 PM
 */

namespace Mezian\Zaina\Http\Requests;

use Mezian\Zaina\Models\Ad;

class AdRequest
{

  public function authorize()
  {
    switch ( $this->method() )
    {
      case 'GET':
      case 'DELETE':
      {
        return false;
      }
      case 'POST':
      {
        return true;
      }
      case 'PUT':
      case 'PATCH':
      {
        return true;
      }
      default:
        return false;
    }
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
          'name'     => 'string',
          'provider' => 'string|min:3',
          'type'     => 'string',
          'place_id' => 'numeric',
          'url'      => 'url',
          'image'    => 'url',
          'active'   => 'boolean',
        ];
      }
      case 'PUT':
      case 'PATCH':
      {
        return [
          'name'     => 'string',
          'provider' => 'string|min:3',
          'type'     => 'string',
          'place_id' => 'numeric',
          'url'      => 'url',
          'image'    => 'url',
          'active'   => 'boolean',
        ];
      }
      default:
        break;
    }
  }

  public function ad()
  {
    $data = request()->all();

    if ( array_key_exists( 'id', $data ) && is_numeric( $data['id'] ) )
    {

      $ad = Ad::findOrFail( intval( $data['id'] ) );

    } else
    {
      /** @var Ad $ad */
      $ad = new Ad();
    }

    $ad->fill( request()->only( [
                                  'name',
                                  'provider',
                                  'type',
                                  'place_id',
                                  'url',
                                  'image',
                                  'active',
                                ] ) );

    $ad->save();

    return $ad;

  }

}