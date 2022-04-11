<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $categories = \DB::connection( 'mysql_old' )->table( 'categories' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0' );
    \DB::table( 'categories' )->delete();
    \DB::table( 'metas' )->where( 'metaable_type', 'Category' )->delete();

    foreach ( $categories as $key => $item )
    {
      if ( ! $item->name )
      {
        continue;
      }
      $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Category::class, 'slug', $item->name );
      if ( mb_strlen( $slug ) > 38 )
      {
        $diff = mb_strlen( $slug ) - 38;
        $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Category::class, 'slug', $item->name, [ 'maxLength' => 38 - $diff ] );
      }
      try
      {
        \DB::table( 'categories' )->insert( [
                                              'id'          => $item->id,
                                              'name'        => trim( $item->name ),
                                              'slug'        => $slug,
                                              'parent_id'   => null,
                                              'description' => trim( $item->name ),
                                              'image'       => null,
                                              'color'       => $item->color ? $item->color : '#000',
                                              'is_active'   => $item->is_active,
                                              'created_by'  => null,
                                              'modified_by' => null,
                                              'created_at'  => date( now() ),
                                              'updated_at'  => date( now() ),
                                            ] );

        \DB::table( 'metas' )->insert( [
                                         'slug'               => $slug,
                                         'seo_title'          => @$item->seo_title ? $item->seo_title : $item->name,
                                         'seo_description'    => @$item->seo_description ? $item->seo_description : substr( $item->description, 0, 297 ),
                                         'is_amp'             => @$item->is_amp ? $item->is_amp : false,
                                         'social_title'       => @$item->social_title ? $item->social_title : ( $item->seo_title ? $item->seo_title : $item->name ),
                                         'social_description' => @$item->social_description ? $item->social_description : substr( $item->description, 0, 297 ),
                                         'social_image'       => @$item->social_image ? $item->social_image : @$item->image,
                                         'is_instant_article' => @$item->is_instant_article ? $item->is_instant_article : false,
                                         'is_index'           => @$item->is_index ? $item->is_index : true,
                                         'is_follow'          => @$item->is_follow ? $item->is_follow : true,
                                         'metaable_type'      => 'Category',
                                         'metaable_id'        => $item->id,
                                       ] );

      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1' );

  }

}
