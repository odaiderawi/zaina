<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mezian\Zaina\Models\BreakingNews;

class BreakingNewsRequest extends FormRequest
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
      case 'PUT':
      case 'PATCH':
      {
        return [
          'content'      => 'required|string|min:15|max:300',
          'is_active'    => 'nullable|boolean',
          'time_to_live' => 'numeric ',
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
      $news = BreakingNews::findOrFail( $this->id );

    } else
    {
      /** @var BreakingNews $news */
      $news = new BreakingNews();
    }

    $news->fill( request()->only( [
                                    'content',
                                    'is_active',
                                    'time_to_live',
                                  ] ) );

    $saved = $news->save();

    if ( $saved )
    {
      return $news;
    }

    return 'cant save breaking news';

  }
}
