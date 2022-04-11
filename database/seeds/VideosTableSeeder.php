<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;

class VideosTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $arr = \DB::connection( 'mysql_old' )->table( 'videos' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( 'videos' )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    \DB::table( 'metas' )->where( 'metaable_type', 'Video' )->delete();

    $users_ids      = \DB::table( 'users' )->select( 'id' )->get()->pluck( 'id' );
    $categories_ids = \DB::table( 'categories' )->select( 'id' )->get()->pluck( 'id' );

    foreach ( $arr as $key => $item )
    {
      if ( ! $item->name )
      {
        continue;
      }
      $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Video::class, 'slug', $item->seo_name ? $item->seo_name : $item->title );
      $diff = mb_strlen( $slug ) - 38;
      while ( mb_strlen( $slug ) > 38 )
      {
        $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Video::class, 'slug', $item->seo_name ? $item->seo_name : $item->title, [ 'maxLength' => 38 - $diff ] );
        $diff += 1;
      }
      try
      {
        \DB::table( 'videos' )->insert( [
                                          'id'              => $item->id,
                                          'name'            => $item->name,
                                          'slug'            => $slug,
                                          'no_of_views'     => $item->no_of_views,
                                          'category_id'     => $item->videocategory_id ? ( $categories_ids->contains( $item->videocategory_id ) ? $item->videocategory_id : 1 ) : 1,
                                          'image'           => $item->image,
                                          'highlight_title' => $item->url_title,
                                          'source'          => $item->source,
                                          'is_main'         => $item->pin_to_main,
                                          'duration'        => $item->duration,
                                          'description'     => $item->description,
                                          'is_disabled'     => $item->is_disabled,
                                          'created_by'      => $item->created_by ? $item->created_by : 1,
                                          'modified_by'     => $item->modified_by ? ( $users_ids->contains( $item->modified_by ) ? $item->modified_by : null ) : null,
                                          'deleted_at'      => null,
                                          'created_at'      => $item->created_at,
                                          'updated_at'      => $item->updated_at,
                                        ] );
        \DB::table( 'metas' )->insert( [
                                         'slug'               => $slug,
                                         'seo_title'          => $item->seo_title,
                                         'seo_description'    => $item->seo_description,
                                         'is_amp'             => false,
                                         'social_title'       => @$item->social_title ? $item->social_title : $item->seo_title,
                                         'social_description' => $item->social_description,
                                         'social_image'       => $item->social_image,
                                         'is_instant_article' => false,
                                         'is_index'           => @$item->is_index ? $item->is_index : true,
                                         'is_follow'          => @$item->is_follow ? $item->is_follow : true,
                                         'metaable_type'      => 'Video',
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
