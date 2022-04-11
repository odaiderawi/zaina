<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;

class PhotoAlbumsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $arr = \DB::connection( 'mysql_old' )->table( 'photoalbums' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( 'photo_albums' )->truncate();
    \DB::table( 'metas' )->where( 'metaable_type', 'PhotoAlbum' )->delete();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    foreach ( $arr as $key => $item )
    {
      if ( ! $item->name )
      {
        continue;
      }
      $slug = SlugService::createSlug( Mezian\Zaina\App\Models\PhotoAlbum::class, 'slug', $item->seo_name ? $item->seo_name : $item->name );
      $diff = mb_strlen( $slug ) - 38;
      while ( mb_strlen( $slug ) > 38 )
      {
        $slug = SlugService::createSlug( Mezian\Zaina\App\Models\PhotoAlbum::class, 'slug', $item->seo_name ? $item->seo_name : $item->name, [ 'maxLength' => 38 - $diff ] );
        $diff += 1;
      }

      try
      {
        $dupNameCount = count( \DB::table( 'photo_albums' )->where( 'name', trim( $item->name ) )->get() );
        if ( $dupNameCount > 0 )
        {
          $item->name = trim( $item->name ) . ' ' . $dupNameCount;
        }
        \DB::table( 'photo_albums' )->insert( [
                                                'id'              => $item->id,
                                                'highlight_title' => $item->name,
                                                'category_id'     => $item->category_id > 0 ? $item->category_id : null,
                                                'news_file_id'    => $item->newsfile_id > 0 ? $item->newsfile_id : null,
                                                'name'            => $item->name,
                                                'slug'            => $slug,
                                                'description'     => $item->description,
                                                'cover_photo'     => $item->cover_photo,
                                                'no_of_views'     => $item->no_of_views,
                                                'is_main'         => $item->is_pin_to_main,
                                                'is_active'       => @$item->is_active ? $item->is_active : true,
                                                'use_watermark'   => $item->use_watermark,
                                                'sort'            => @$item->sort ? $item->sort : 0,
                                                'created_by'      => $item->created_by ? $item->created_by : 1,
                                                'modified_by'     => $item->modified_by,
                                                'deleted_at'      => null,
                                                'created_at'      => $item->created_at,
                                                'updated_at'      => $item->updated_at,
                                              ] );

        \DB::table( 'metas' )->insert( [
                                         'slug'               => $slug,
                                         'seo_title'          => @$item->seo_title ? $item->seo_title : $item->name,
                                         'seo_description'    => @$item->seo_description ? $item->seo_description : ( $item->description ? str_limit( $item->description, 297, '' ) : ( $item->seo_title ? $item->seo_title : $item->name ) ),
                                         'is_amp'             => @$item->is_amp ? $item->is_amp : false,
                                         'social_title'       => @$item->social_title ? $item->social_title : ( $item->seo_title ? $item->seo_title : $item->name ),
                                         'social_description' => @$item->social_description ? $item->social_description : ( $item->description ? str_limit( $item->description, 297, '' ) : ( $item->seo_title ? $item->seo_title : $item->name ) ),
                                         'social_image'       => @$item->social_image ? $item->social_image : @$item->image,
                                         'is_instant_article' => @$item->is_instant_article ? $item->is_instant_article : false,
                                         'is_index'           => @$item->is_index ? $item->is_index : true,
                                         'is_follow'          => @$item->is_follow ? $item->is_follow : true,
                                         'metaable_type'      => 'PhotoAlbum',
                                         'metaable_id'        => $item->id,
                                       ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }
  }
}
