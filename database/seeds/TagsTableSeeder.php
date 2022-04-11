<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $tags = \DB::connection( 'mysql_old' )->table( 'tags' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( 'tags' )->delete();
    \DB::table( 'metas' )->where( 'metaable_type', 'Tag' )->delete();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    foreach ( $tags as $key => $item )
    {
      $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Tag::class, 'slug', $item->name );
      $diff = mb_strlen( $slug ) - 38;
      while ( mb_strlen( $slug ) > 38 )
      {
        $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Tag::class, 'slug', $item->name, [ 'maxLength' => 38 - $diff ] );
        $diff += 1;
      }
      try
      {
        $dupTag = \DB::table( 'tags' )->where( 'name', trim( $item->name ) )->first();
        if ( $dupTag )
        {
          \DB::table( 'tags' )->where( 'id', $dupTag->id )->update( [ 'no_of_uses' => $dupTag->no_of_uses + $item->no_of_uses ] );
        } else
        {
          \DB::table( 'tags' )->insert( [
                                          'id'          => $item->id,
                                          'name'        => trim( $item->name ),
                                          'slug'        => $slug,
                                          'description' => $item->description,
                                          'no_of_uses'  => $item->no_of_uses,
                                          'is_disabled' => false,
                                          'created_by'  => $item->created_by,
                                          'modified_by' => $item->modified_by,
                                          'created_at'  => $item->created_at,
                                          'updated_at'  => $item->updated_at,
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
                                           'metaable_type'      => 'Tag',
                                           'metaable_id'        => $item->id,
                                         ] );
        }

      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }

  }
}
