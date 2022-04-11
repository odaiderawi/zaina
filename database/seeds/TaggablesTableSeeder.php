<?php

use Illuminate\Database\Seeder;

class TaggablesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $tablesArr['Article'] = \DB::connection( 'mysql_old' )->table( 'article_tag' )->get();
    $tablesArr['News']    = \DB::connection( 'mysql_old' )->table( 'news_tag' )->get();
//        $tablesArr['PhotoAlbum'] = \DB::connection('mysql_old')->table('photoalbum_tag')->get();
//        $tablesArr['Video'] = \DB::connection('mysql_old')->table('tag_video')->get();

    $columns['Article'] = 'article_id';
    $columns['News']    = 'news_id';
//        $columns['PhotoAlbum'] = 'photoalbum_id';
//        $columns['Video'] = 'video_id';

    \DB::table( 'taggables' )->truncate();

    foreach ( $tablesArr as $type => $arr )
    {
      foreach ( $arr as $item )
      {
        try
        {
          $oldTag = \DB::connection( 'mysql_old' )->table( 'tags' )->where( 'id', $item->tag_id )->first();
          if ( ! $oldTag )
          {
            continue;
          }

          \DB::table( 'taggables' )->insert( [
                                               'tag_id'        => \Mezian\Zaina\App\Models\Tag::where( 'name', trim( $oldTag->name ) )->first()->id,
                                               'taggable_type' => $type,
                                               'taggable_id'   => $item->{$columns[ $type ]},
                                             ] );
        } catch ( \Illuminate\Database\QueryException $e )
        {
          $this->command->error( $e->getMessage() );
          $this->command->error( '' );
        }
      }
    }
  }
}
