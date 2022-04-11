<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mezian\Zaina\Models\Tag;

class TagRequest extends FormRequest
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
        return $this->user() != null;
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
      {
        return [
          'name'        => 'required|string|min:3|max:200|unique:tags',
          'description' => 'nullable|string|min:3',
          'is_disabled' => 'boolean',
        ];
      }
      case 'PUT':
      case 'PATCH':
      {
        return [
          'name'        => 'required|string|min:3|max:200',
          'description' => 'nullable|string|min:3',
          'is_disabled' => 'boolean',
        ];
      }
      default:
        break;
    }
  }

  public function tag()
  {

    if ( $this->id )
    {

      $tag = Tag::findOrFail( $this->id );

    } else if ( Tag::where( 'name', $this->name )->first() != null )
    {

      return response()->json( [ 'error', 'tag name exist' ], 500 );

    } else
    {
      /** @var Tag $tag */
      $tag = new Tag();
    }

    $tag->fill( request()->only( [
                                   'name',
                                   'description',
                                   'is_disabled',
                                   'seo_description',
                                 ] ) );

    $saved = $tag->save();

    if ( $saved )
    {
      return response()->json( [ 'success', $tag ], 200 );
    }

    return response()->json( [ 'error', 'can not save tag' . $tag->id ], 500 );

  }
}
