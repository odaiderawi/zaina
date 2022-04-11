<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mezian\Zaina\Models\Tag;
use Mezian\Zaina\Models\Video;

class VideoRequest extends FormRequest
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
          'category_id'     => 'exists:categories,id',
          'highlight_title' => 'string|min:3|max:200',
          'name'            => 'required|string|min:3|max:200',
          'image'           => 'required|string',
          'description'     => 'required|string|min:10',
          'source'          => 'required_without:url|url',
          'url'             => 'required_without:source',
          'duration'        => 'string|min:5|max:8',
        ];
      }
      case 'PUT':
      case 'PATCH':
      {
        return [
          'category_id'     => 'exists:categories,id',
          'highlight_title' => 'string|min:3|max:200',
          'name'            => 'required|string|min:3|max:200',
          'image'           => 'required|string',
          'description'     => 'required|string|min:10',
          'source'          => 'required_without:url|url',
          'url'             => 'required_without:source',
          'duration'        => 'string|min:5|max:8',
        ];
      }
      default:
        break;
    }
  }

  public function video()
  {
    $data = request()->all();

    if ( array_key_exists( 'id', $data ) && is_numeric( $data['id'] ) )
    {

      $video = Video::findOrFail( intval( $data['id'] ) );

    } else
    {
      /** @var Video $video */
      $video = new Video();
    }

    $video->fill( request()->only( [
                                     'highlight_title',
                                     'image',
                                     'description',
                                     'category_id',
                                     'name',
                                     'date_to_publish',
                                     'source',
                                     'duration',
                                     'is_main',
                                     'is_youtube',
                                     'url',
                                   ] ) );

    $saved = $video->save();

    if ( array_key_exists( 'tags', $data ) && count( $data['tags'] ) > 0 && $data['tags'] != '' )
    {
      Tag::tags( $video, $data['tags'] );
    }

    if ( $saved )
    {
      return 'video saved successfully';
    }

    return 'cant save article' . $video->id;

  }

}
