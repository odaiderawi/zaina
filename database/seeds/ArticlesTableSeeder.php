<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $articles = \DB::connection( 'mysql_old' )->table( 'articles' )->where( [
                                                                              [ 'title', '!=', null ],
                                                                              [ 'content', '!=', null ],
                                                                            ] )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( 'articles' )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    \DB::table( 'metas' )->where( 'metaable_type', 'Article' )->delete();

    $authors_ids = \DB::table( 'authors' )->select( 'id' )->pluck( 'id' );

    foreach ( $articles as $key => $item )
    {
      $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Article::class, 'slug', $item->seo_name ? $item->seo_name : $item->title );
      $diff = mb_strlen( $slug ) - 38;
      while ( mb_strlen( $slug ) > 38 )
      {
        $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Article::class, 'slug', $item->seo_name ? $item->seo_name : $item->title, [ 'maxLength' => 38 - $diff ] );
        $diff += 1;
      }
      try
      {
        \DB::table( 'articles' )->insert( [
                                            'id'                    => $item->id,
                                            'title'                 => $item->title,
                                            'author_id'             => $authors_ids->contains( $item->author_id ) ? $item->author_id : null,
                                            'slug'                  => $slug,
                                            'no_of_views'           => $item->no_of_views ? $item->no_of_views : 0,
                                            'category_id'           => $item->category_id,
                                            'image'                 => $item->image ?? '...',
                                            'image_description'     => $item->image_description,
                                            'highlight_title'       => $item->url_title,
                                            'source'                => $item->source,
                                            'content'               => $item->content,
                                            'summary'               => $item->summary,
                                            'is_article_ticker'     => $item->is_article_ticker,
                                            'is_main_article'       => $item->is_main_article,
                                            'is_special_article'    => $item->is_special_article,
                                            'is_particular_article' => $item->is_particular_article,
                                            'is_shown_in_template'  => $item->is_shown_in_template,
                                            'is_share_to_facebook'  => false,
                                            'is_share_to_twitter'   => false,
                                            'is_draft'              => $item->is_draft,
                                            'is_archived'           => $item->is_archived ?? false,
                                            'use_watermark'         => false,
                                            'is_disabled'           => $item->is_disabled,
                                            'created_by'            => $item->created_by,
                                            'modified_by'           => $item->modified_by,
                                            'date_to_publish'       => $item->date_to_publish,
                                            'deleted_at'            => $item->is_deleted ? $item->updated_at : null,
                                            'created_at'            => $item->created_at,
                                            'updated_at'            => $item->updated_at,
                                          ] );
        \DB::table( 'metas' )->insert( [
                                         'slug'               => $slug,
                                         'seo_title'          => @$item->seo_title ? $item->seo_title : $item->title,
                                         'seo_description'    => @$item->seo_description ? $item->seo_description : str_limit( $item->content, 297, '' ),
                                         'is_amp'             => @$item->is_amp ? $item->is_amp : false,
                                         'social_title'       => @$item->seo_title ? $item->seo_title : $item->title,
                                         'social_description' => @$item->social_description ? $item->social_description : str_limit( $item->content, 297, '' ),
                                         'social_image'       => @$item->social_image ? $item->social_image : @$item->image,
                                         'is_instant_article' => @$item->is_instant_article ? $item->is_instant_article : false,
                                         'is_index'           => @$item->is_index ? $item->is_index : true,
                                         'is_follow'          => @$item->is_follow ? $item->is_follow : true,
                                         'metaable_type'      => 'Article',
                                         'metaable_id'        => $item->id,
                                       ] );

      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
        continue;
      }
    }
  }
}
