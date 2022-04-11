<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $arr = \DB::connection( 'mysql_old' )->table( 'news' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( 'news' )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    \DB::table( 'metas' )->where( 'metaable_type', 'News' )->delete();

    $files_ids      = \DB::table( 'news_files' )->select( 'id' )->get()->pluck( 'id' );
    $users_ids      = \DB::table( 'users' )->select( 'id' )->get()->pluck( 'id' );
    $categories_ids = \DB::table( 'categories' )->select( 'id' )->get()->pluck( 'id' );
    $types_ids      = \DB::table( 'types' )->select( 'id' )->get()->pluck( 'id' );

    foreach ( $arr as $key => $item )
    {
      if ( ! $item->title )
      {
        continue;
      }
      $slug = SlugService::createSlug( Mezian\Zaina\App\Models\News::class, 'slug', $item->seo_name ? $item->seo_name : $item->title );
      $diff = mb_strlen( $slug ) - 38;
      while ( mb_strlen( $slug ) > 38 )
      {
        $slug = SlugService::createSlug( Mezian\Zaina\App\Models\News::class, 'slug', $item->seo_name ? $item->seo_name : $item->title, [ 'maxLength' => 38 - $diff ] );
        $diff += 1;
      }
      try
      {
        \DB::table( 'news' )->insert( [
                                        'id'                   => $item->id,
                                        'title'                => $item->title,
                                        'slug'                 => $slug,
                                        'no_of_views'          => $item->no_of_views,
                                        'type_id'              => $item->type_id ? ( $types_ids->contains( $item->type_id ) ? $item->type_id : null ) : null,
                                        'category_id'          => $item->category_id ? ( $categories_ids->contains( $item->category_id ) ? $item->category_id : 2 ) : 2,
                                        'news_file_id'         => $item->newsfile_id ? ( $files_ids->contains( $item->newsfile_id ) ? $item->newsfile_id : null ) : null,
                                        'photo_album_id'       => $item->photoalbum_id,
                                        'playlist_id'          => $item->playlist_id,
                                        'video'                => $item->video,
                                        //                    'is_notify' => $item->is_notify,
                                        //                    'is_notified' => $item->is_notify,
                                        'image'                => $item->image ?? '...',
                                        'image_description'    => $item->image_description,
                                        'highlight_title'      => $item->url_title,
                                        'source'               => $item->source,
                                        'content'              => $item->content ?? '...',
                                        'summary'              => $item->summary,
                                        'is_news_ticker'       => $item->is_news_ticker,
                                        'is_main_news'         => $item->is_main_news,
                                        'is_special_news'      => $item->is_special_news,
                                        'is_particular_news'   => $item->is_particular_news,
                                        'is_shown_in_template' => $item->is_shown_in_template,
                                        'is_share_to_facebook' => false,
                                        'is_share_to_twitter'  => false,
                                        'is_draft'             => $item->is_draft,
                                        'is_archived'          => $item->is_archived,
                                        'is_no_index'          => $item->is_noindex,
                                        'use_watermark'        => @$item->use_watermark ? $item->use_watermark : false,
                                        'is_disabled'          => $item->is_disabled,
                                        'created_by'           => $item->created_by ? $item->created_by : 1,
                                        'modified_by'          => $item->modified_by ? ( $users_ids->contains( $item->modified_by ) ? $item->modified_by : null ) : null,
                                        'date_to_publish'      => $item->date_to_publish ? $item->date_to_publish : ( $item->created_at ? $item->created_at : $item->updated_at ),
                                        'deleted_at'           => $item->is_deleted ? $item->updated_at : null,
                                        'created_at'           => $item->created_at ? $item->created_at : $item->updated_at,
                                        'updated_at'           => $item->updated_at,
                                      ] );
        \DB::table( 'metas' )->insert( [
                                         'slug'               => $slug,
                                         'seo_title'          => @$item->seo_title ? $item->seo_title : $item->title,
                                         'seo_description'    => @$item->seo_description ? $item->seo_description : str_limit( $item->content ?? '...', 297, '' ),
                                         'is_amp'             => @$item->is_amp ? $item->is_amp : false,
                                         'social_title'       => @$item->seo_title ? $item->seo_title : $item->title,
                                         'social_description' => @$item->social_description ? $item->social_description : str_limit( $item->content ?? '...', 297, '' ),
                                         'social_image'       => @$item->social_image ? $item->social_image : @$item->image,
                                         'is_instant_article' => @$item->is_instant_article ? $item->is_instant_article : false,
                                         'is_index'           => @$item->is_index ? $item->is_index : true,
                                         'is_follow'          => @$item->is_follow ? $item->is_follow : true,
                                         'metaable_type'      => 'News',
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
