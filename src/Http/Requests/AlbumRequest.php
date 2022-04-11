<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Mezian\Zaina\Models\Article;
use Mezian\Zaina\Models\Author;
use Mezian\Zaina\Models\PhotoAlbum;
use Mezian\Zaina\Models\Tag;

class AlbumRequest extends FormRequest
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
          'highlight_title' => 'nullable|string|min:3|max:200',
          'name'            => 'string|min:6|max:200',
          'description'     => 'nullable|string',
          'cover_photo'     => 'string|max:200',
          'is_main'         => 'nullable|boolean',
          'is_active'       => 'nullable|boolean',
          'use_watermark'   => 'nullable|boolean',
        ];
      }
      case 'PUT':
      case 'PATCH':
      {
        return [
          'highlight_title' => 'nullable|string|min:3|max:200',
          'name'            => 'string|min:6|max:200',
          'description'     => 'nullable|string',
          'cover_photo'     => 'string|max:200',
          'is_main'         => 'nullable|boolean',
          'is_active'       => 'nullable|boolean',
          'use_watermark'   => 'nullable|boolean',
        ];
      }
      default:
        break;
    }
  }

  public function album()
  {
    $data = request()->all();

    if ( array_key_exists( 'id', $data ) && is_numeric( $data['id'] ) )
    {

      $album = PhotoAlbum::findOrFail( intval( $data['id'] ) );

    } else
    {
      /** @var PhotoAlbum $album */
      $album = new PhotoAlbum();
    }

    $album->fill( request()->only( [
                                     'highlight_title',
                                     'name',
                                     'description',
                                     'cover_photo',
                                     'is_main',
                                     'is_active',
                                     'use_watermark',
                                   ] ) );

    $saved = $album->save();

    $output = collect( $data['photos'] )->map( function ( $value ) {
      if ( isset( $value['url'] ) )
      {
        return $value['url'];
      } else if ( ! is_array( $value ) )
      {
        return $value;
      } else
      {
        return implode( ' ', $value );
      }
    } );

    $album->photos()->delete();
    foreach ( $output as $photo )
    {
      $album->photos()->create( [
                                  'url'            => $photo,
                                  'description'    => 'description',
                                  'name'           => 'name',
                                  'photo_album_id' => $album->id,
                                ] );
    }

    if ( $saved )
    {
      return $album;
    }

    return 'cant save article' . $album->id;

  }
}
