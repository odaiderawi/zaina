<?php

namespace Mezian\Zaina\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mezian\Zaina\Models\Setting;

class SettingRequest extends FormRequest
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
          'website_url'                       => 'url',
          'logo'                              => 'url',
          'default_news_image'                => 'url',
          'favourite_icon'                    => 'url',
          'copyrights'                        => 'string',
          'about'                             => 'string',
          'email'                             => 'email',
          'phone_number'                      => 'nullable|string|max:100',
          'mobile_number'                     => 'nullable|string',
          'address'                           => 'nullable|string',
          'no_of_main_news'                   => 'numeric',
          'close_website'                     => 'boolean',
          'closing_message_title'             => 'nullable|string',
          'content_of_closing_message'        => 'nullable|string',
          'use_watermarks'                    => 'boolean',
          'watermakrs_url'                    => 'url',
          'website_name'                      => 'string',
          'name_on_search_engines'            => 'string',
          'name_on_google_news'               => 'string',
          'separator_used_in_url'             => 'string',
          'website_motto'                     => 'string',
          'website_description'               => 'string',
          'keywords'                          => 'string',
          'socialmedia_name'                  => 'string',
          'socialmedia_description'           => 'string',
          'socailmedia_image'                 => 'url',
          'facebook_account'                  => 'nullable|string',
          'google_account'                    => 'nullable|string',
          'twitter_account'                   => 'nullable|string',
          'using_large_image_on_twitter'      => 'boolean',
          'instagram_account'                 => 'nullable|string',
          'facebook_app_number'               => 'nullable|string',
          'facebook_access_token'             => 'nullable|string',
          'display_news_title_search_engine'  => 'nullable|string',
          'website_description_search_engine' => 'nullable|string',
          'rss'                               => 'boolean',
          'robotes_content'                   => 'nullable|string',
        ];
      }
      case 'PUT':
      case 'PATCH':
      {
        return [];
      }
      default:
        break;
    }
  }

  public function update()
  {
    $settings = request()->all();

    foreach ( $settings as $key => $value )
    {
      Setting::where( 'key', $key )->update( [ 'value' => $value ] );
    }

    return response()->json( [ 'تم تعديل الاعدادت بنجاح' ], 200 );

  }

}
