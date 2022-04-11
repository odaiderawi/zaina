<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $arr = \DB::connection( 'mysql_old' )->table( 'pages' )->get();
    \DB::table( 'pages' )->truncate();

    \DB::table( 'metas' )->where( 'metaable_type', 'Page' )->delete();

    foreach ( $arr as $key => $item )
    {

      $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Page::class, 'slug', $item->seo_name ? $item->seo_name : $item->name );
      $diff = mb_strlen( $slug ) - 38;
      while ( mb_strlen( $slug ) > 38 )
      {
        $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Page::class, 'slug', $item->seo_name ? $item->seo_name : $item->name, [ 'maxLength' => 38 - $diff ] );
        $diff += 1;
      }

      try
      {
        \DB::table( 'pages' )->insert( [
                                         'id'          => $item->id,
                                         'name'        => $item->name,
                                         'slug'        => $slug,
                                         'description' => $item->description,
                                         'content'     => $item->content,
                                         'is_active'   => $item->is_published,
                                         'is_draft'    => $item->is_draft,
                                         'created_by'  => $item->created_by ? $item->created_by : 1,
                                         'modified_by' => $item->modified_by,
                                         'deleted_at'  => @$item->deleted_at,
                                         'created_at'  => $item->created_at,
                                         'updated_at'  => $item->updated_at,
                                       ] );
        \DB::table( 'metas' )->insert( [
                                         'slug'               => $slug,
                                         'seo_title'          => $item->seo_title,
                                         'seo_description'    => $item->seo_description,
                                         'is_amp'             => @$item->is_amp ? $item->is_amp : false,
                                         'social_title'       => @$item->social_title ? $item->social_title : $item->seo_title,
                                         'social_description' => @$item->social_description ? $item->social_description : $item->seo_title,
                                         'social_image'       => @$item->social_image,
                                         'is_instant_article' => @$item->is_instant_article ? $item->is_instant_article : false,
                                         'is_index'           => @$item->is_index ? $item->is_index : true,
                                         'is_follow'          => @$item->is_follow ? $item->is_follow : true,
                                         'metaable_type'      => 'Page',
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
