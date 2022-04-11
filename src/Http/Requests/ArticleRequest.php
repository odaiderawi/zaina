<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Mezian\Zaina\Models\Article;
use Mezian\Zaina\Models\Author;
use Mezian\Zaina\Models\Tag;

class ArticleRequest extends FormRequest
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
          'title'                 => 'nullable|string|min:3|max:200',
          'category_id'           => 'nullable|exists:categories,id',
          'image'                 => 'nullable|string',
          'image_description'     => 'nullable|string|max:200',
          'highlight_title'       => 'nullable|string|max:200',
          'source'                => 'nullable|string|max:100',
          'content'               => 'nullable|string',
          'is_article_ticker'     => 'boolean',
          'is_main_article'       => 'boolean',
          'is_special_article'    => 'boolean',
          'is_particular_article' => 'boolean',
          'is_shown_in_template'  => 'boolean',
          'is_share_to_facebook'  => 'boolean',
          'is_share_to_twitter'   => 'boolean',
          'use_watermark'         => 'boolean',
        ];
      }
      case 'POST':
      {
        return [
          'title'                 => 'required|string|min:3|max:200',
          'category_id'           => 'exists:categories,id',
          'type_id'               => 'exists:types,id',
          'news_file_id'          => 'exists:news_files,id',
          'image'                 => 'required|string',
          'image_description'     => 'nullable|string|max:200',
          'highlight_title'       => 'string|max:200',
          'source'                => 'string|max:100',
          'content'               => 'required|string',
          'is_article_ticker'     => 'boolean',
          'is_main_article'       => 'boolean',
          'is_special_article'    => 'boolean',
          'is_particular_article' => 'boolean',
          'is_shown_in_template'  => 'boolean',
          'is_share_to_facebook'  => 'boolean',
          'is_share_to_twitter'   => 'boolean',
          'use_watermark'         => 'boolean',
        ];
      }
      case 'PUT':
      case 'PATCH':
      {
        return [
          'title'                 => 'required|string|min:3|max:200',
          'category_id'           => 'exists:categories,id',
          'image'                 => 'required|string',
          'image_description'     => 'nullable|string|max:200',
          'highlight_title'       => 'nullable|string|max:200',
          'source'                => 'nullable|string|max:100',
          'content'               => 'required|string',
          'is_article_ticker'     => 'boolean',
          'is_main_article'       => 'boolean',
          'is_special_article'    => 'boolean',
          'is_particular_article' => 'boolean',
          'is_shown_in_template'  => 'boolean',
          'is_share_to_facebook'  => 'boolean',
          'is_share_to_twitter'   => 'boolean',
          'use_watermark'         => 'boolean',
        ];
      }
      default:
        break;
    }
  }

  public function articles()
  {
    $data = request()->all();

    if ( array_key_exists( 'id', $data ) && is_numeric( $data['id'] ) )
    {

      $article = Article::findOrFail( intval( $data['id'] ) );

    } else
    {
      /** @var Article $article */
      $article = new Article();
    }

    $article->fill( request()->only( [
                                       'title',
                                       'image',
                                       'content',
                                       'category_id',
                                       'date_to_publish',
                                       'is_article_ticker',
                                       'is_main_article',
                                       'is_special_article',
                                       'is_particular_article',
                                       'is_shown_in_template',
                                       'is_share_to_facebook',
                                       'is_share_to_twitter',
                                       'summary',
                                       'source',
                                       'highlight_title',
                                       'image_description',
                                       'use_watermark',
                                       'author_id',
                                     ] ) );

    $saved = $article->save();

    if ( array_key_exists( 'tags', $data ) && count( $data['tags'] ) > 0 && $data['tags'] != '' )
    {
      Tag::tags( $article, $data['tags'] );
    }

    if ( array_key_exists( 'author', $data ) && is_array( $data['author'] ) && $data['author'] != '' )
    {
      Author::author( $article, $data['author'] );
    }

    return $article;

  }
}
