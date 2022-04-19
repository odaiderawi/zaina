<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mezian\Zaina\Models\Tag;

class FileRequest extends FormRequest
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
          'file' => 'max:10240|required|mimes:xlsx,docx,jpeg,bmp,png,jpg,gif,pdf,mp4,svg,mp3,webp',
        ];
      }
      case 'PUT':
      case 'PATCH':
      {
        return [
          'file' => 'required',
        ];
      }
      default:
        break;
    }
  }

}
