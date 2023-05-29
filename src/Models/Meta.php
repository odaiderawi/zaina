<?php

namespace Mezian\Zaina\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Meta extends Model
{
  protected $fillable = [
    'slug',
    'seo_title',
    'seo_description',
    'is_amp',
    'social_title',
    'social_description',
    'social_image',
    'is_instant_article',
  ];

  public    $timestamps = false;
  protected $primaryKey = 'slug';

  public function metaable()
  {
    return $this->morphTo();
  }

  public function setSeo_descriptionAttribute( $value )
  {
    $this->seo_description = strip_tags( $value );
  }

  public function setSocial_descriptionAttribute( $value )
  {
    $this->seo_description = strip_tags( $value );
  }

  public static function data( $model, $data )
  {
    if ( \request()->is( '*/status' ) )
    {
      return '';
    }
    if ( ( $metas = Meta::where( 'metaable_id', $model->id )->where( 'metaable_type', $model->getMorphClass() )->first() ) != null )
    {
      Log::info( 'Meta', $data );
      return $metas->update( [
                               'slug'               => $model->slug,
                               'seo_title'          => @$data['seo_title'] ? @$data['seo_title'] : ( @$data['title'] ? @$data['title'] : ( @$data['name'] ? @$data['name'] : '' ) ),
                               'seo_description'    => $data['seo_description'],
                               'is_amp'             => @$data['is_amp'],
                               'social_title'       => @$data['social_title'] ? @$data['social_title'] : ( @$data['title'] ? @$data['title'] : ( @$data['name'] ? @$data['name'] : '' ) ),
                               'social_description' => @$data['social_description'] ? @$data['social_description'] : ( @$data['content'] ? str_limit( @$data['content'], 297, '' ) : ( @$data['description'] ? str_limit( @$data['description'], 297, '' ) : '' ) ),
                               'social_image'       => @$data['image'] ? $data['image'] : ( @$data['image'] ? @$data['image'] : ( @$data['cover_photo'] ? @$data['cover_photo'] : '' ) ),
                               'is_instant_article' => @$data['is_instant_article'],
                               'is_index'           => @$data['is_index'],
                               'is_follow'          => @$data['is_follow'],
                             ] );
    }

    $metas = new Meta( [
                         'slug'               => $model->slug,
                         'seo_title'          => @$data['seo_title'] ? $data['seo_title'] : ( @$data['title'] ? @$data['title'] : ( @$data['name'] ? @$data['name'] : '' ) ),
                         'seo_description'    => @$data['seo_description'] ? $data['seo_description'] : ( @$data['content'] ? str_limit( @$data['content'], 297, '' ) : ( @$data['description'] ? str_limit( @$data['description'], 297, '' ) : '' ) ),
                         'is_amp'             => @$data['is_amp'],
                         'social_title'       => @$data['social_title'] ? $data['social_title'] : ( @$data['title'] ? @$data['title'] : ( @$data['name'] ? @$data['name'] : '' ) ),
                         'social_description' => @$data['social_description'] ? $data['social_description'] : ( @$data['content'] ? str_limit( @$data['content'], 297, '' ) : ( @$data['description'] ? str_limit( @$data['description'], 297, '' ) : '' ) ),
                         'social_image'       => @$data['social_image'] ? $data['social_image'] : ( @$data['image'] ? @$data['image'] : ( @$data['cover_photo'] ? @$data['cover_photo'] : '' ) ),
                         'is_instant_article' => @$data['is_instant_article'],
                         'is_index'           => @$data['is_index'],
                         'is_follow'          => @$data['is_follow'],
                       ] );

    return $model->metas()->save( $metas );

  }

}
