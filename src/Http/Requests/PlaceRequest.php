<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {

    return [
      'name'       => 'string|required|min:6|max:60',
      'identifier' => 'string|required|min:6|max:60',
      'type'       => 'string|required',
      'width'      => 'numeric',
      'height'     => 'numeric',
      'active'     => 'boolean',
    ];
  }

}
