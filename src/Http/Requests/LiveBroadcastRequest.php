<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mezian\Zaina\Models\Category;
use Mezian\Zaina\Models\LiveBroadcast;

class LiveBroadcastRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
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
        return $this->user() != null;
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
      case 'PUT':
      case 'PATCH':
      {
        return [
          'title'     => 'required|string|min:3|max:200',
          'type'      => 'string',
          'url'       => 'url',
          'is_active' => 'boolean',
        ];
      }
      default:
        break;
    }
  }

  public function save()
  {

    if ( $this->id )
    {
      $live = LiveBroadcast::findOrFail( $this->id );

    } else
    {
      /** @var LiveBroadcast $live */
      $live = new LiveBroadcast();
    }

    $live->fill( request()->only( [
                                    'title',
                                    'type',
                                    'url',
                                  ] ) );

    $live->is_active = request( 'active' );

    $saved = $live->save();

    if ( $saved )
    {
      return $live;
    }

    return 'cant save Live Broadcast';

  }
}
