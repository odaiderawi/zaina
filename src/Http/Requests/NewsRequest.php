<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Mezian\Zaina\Models\Meta;
use Mezian\Zaina\Models\News;
use Mezian\Zaina\Models\Photo;
use Mezian\Zaina\Models\Tag;

class NewsRequest extends FormRequest
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
      case ( 'POST' && Request::is( '*/draft' ) ):
      {
        return [
          'title'                => 'nullable|string|min:3|max:200',
          'category_id'          => 'nullable|exists:categories,id',
          'type_id'              => 'nullable|exists:types,id',
          'news_file_id'         => 'nullable|exists:news_files,id',
          'image'                => 'nullable|string',
          'image_description'    => 'nullable|string|max:200',
          'highlight_title'      => 'nullable|string|max:200',
          'source'               => 'nullable|string|max:100',
          'content'              => 'nullable|string',
          'summary'              => 'nullable|string',
          'video'                => 'nullable|url',
          'is_news_ticker'       => 'nullable|boolean',
          'is_main_news'         => 'nullable|boolean',
          'is_special_news'      => 'nullable|boolean',
          'is_particular_news'   => 'nullable|boolean',
          'is_shown_in_template' => 'nullable|boolean',
          'is_share_to_facebook' => 'nullable|boolean',
          'is_share_to_twitter'  => 'nullable|boolean',
          'use_watermark'        => 'nullable|boolean',
        ];
      }
      case 'POST':
      {
        return [
          'title'                => 'required|string|min:3|max:200',
          'category_id'          => 'required|exists:categories,id',
          'type_id'              => 'nullable|exists:types,id',
          'news_file_id'         => 'nullable|exists:news_files,id',
          'image'                => 'required|string',
          'image_description'    => 'nullable|string|max:200',
          'highlight_title'      => 'nullable|string|max:200',
          'source'               => 'nullable|string|max:100',
          'content'              => 'required|string',
          'summary'              => 'nullable|string',
          'video'                => 'nullable|url',
          'is_news_ticker'       => 'boolean',
          'is_main_news'         => 'boolean',
          'is_special_news'      => 'boolean',
          'is_particular_news'   => 'boolean',
          'is_shown_in_template' => 'boolean',
          'is_share_to_facebook' => 'boolean',
          'is_share_to_twitter'  => 'boolean',
          'use_watermark'        => 'boolean',
        ];
      }
      case 'PUT':
      case 'PATCH':
      {
        return [
          'title'                => 'required|string|min:3|max:200',
          'category_id'          => 'required|exists:categories,id',
          'type_id'              => 'nullable|exists:types,id',
          'news_file_id'         => 'nullable|exists:news_files,id',
          'image'                => 'required|string',
          'image_description'    => 'nullable|string|max:200',
          'highlight_title'      => 'nullable|string|max:200',
          'source'               => 'nullable|string|max:100',
          'content'              => 'required|string',
          'summary'              => 'nullable|string',
          'video'                => 'nullable|url',
          'is_news_ticker'       => 'boolean',
          'is_main_news'         => 'boolean',
          'is_special_news'      => 'boolean',
          'is_particular_news'   => 'boolean',
          'is_shown_in_template' => 'boolean',
          'is_share_to_facebook' => 'boolean',
          'is_share_to_twitter'  => 'boolean',
          'use_watermark'        => 'boolean',
        ];
      }
      default:
        break;
    }
  }

  public function news()
  {
    $data = request()->all();

    if ( array_key_exists( 'id', $data ) && is_numeric( $data['id'] ) )
    {

      $news = News::findOrFail( intval( $data['id'] ) );

      Meta::data( $news, $data );

    } else
    {
      /** @var News $news */
      $news = new News();
    }
    $news->fill( request()->only( [
                                    'title',
                                    'image',
                                    'content',
                                    'category_id',
                                    'video',
                                    'date_to_publish',
                                    'is_news_ticker',
                                    'is_main_news',
                                    'is_special_news',
                                    'is_particular_news',
                                    'is_shown_in_template',
                                    'is_share_to_facebook',
                                    'is_share_to_twitter',
                                    'summary',
                                    'source',
                                    'highlight_title',
                                    'image_description',
                                    'news_file_id',
                                    'type_id',
                                    'photo_album_id',
                                    'playlist_id',
                                    'use_watermark',
                                  ] ) );

    $news->save();

    Photo::with( [] )->where( 'news_id', $news->id )->delete();
    foreach ( \request()->input( 'photos', [] ) as $photo )
    {
      Photo::with( [] )->create( [
                                   'news_id' => $news->id,
                                   'url'     => str_replace( 'https://al-aalem.com/storage/', '', $photo ),
                                 ] );
    }

    if ( array_key_exists( 'tags', $data ) && count( $data['tags'] ) > 0 && $data['tags'] != '' )
    {
      Tag::tags( $news, $data['tags'] );
    }

    if ( array_key_exists( 'share_facebook', $data ) && $data['share_facebook'] === true && $this->method() == 'POST' && ( ! Request::is( '*/draft' ) ) )
    {
      News::shareFB( $news );
    }

    if ( array_key_exists( 'twitter', $data ) && $data['twitter'] === true && $this->method() == 'POST' && ( ! Request::is( '*/draft' ) ) )
    {
      News::shareTwitter( $news );
    }

//        if ($this->method() == 'POST' && (!Request::is('*/draft'))) News::pushNotification($news);

    return $news;
  }

}
