<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mezian\Zaina\Models\Category;

class CategoryRequest extends FormRequest
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
          'name'        => 'required|string|min:3|max:200',
          'parent_id'   => 'nullable|exists:categories,id',
          'description' => 'required|string',
          'image'       => 'nullable|string|max:200',
          'color'       => 'nullable|string|min:4|max:8',
          'is_active'   => 'boolean',
        ];
      }
      default:
        break;
    }
  }

  public function category()
  {

    if ( $this->id )
    {
      $category = Category::findOrFail( $this->id );

    } else
    {
      /** @var Category $category */
      $category = new Category();
    }

    $category->fill( request()->only( [
                                        'name',
                                        'parent_id',
                                        'description',
                                        'image',
                                        'color',
                                        'is_active',
                                        'show_in_home',
                                        'sort',
                                        'show_in_nav',
                                        'is_file',
                                      ] ) );

    if ( ! ( strlen( request( 'name' ) ) != strlen( utf8_decode( request( 'name' ) ) ) ) )
    {
      $category->is_english = 1;
    }

    $saved = $category->save();

    if ( $saved )
    {
      return $category;
    }

    return 'cant save category' . $category->id;

  }
}
