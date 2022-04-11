<?php

use Illuminate\Database\Seeder;

class PhotosTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $arr = \DB::connection( 'mysql_old' )->table( 'photos' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( 'photos' )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    $users_ids  = \DB::table( 'users' )->select( 'id' )->get()->pluck( 'id' );
    $albums_ids = \DB::table( 'photo_albums' )->select( 'id' )->get()->pluck( 'id' );
    $news_ids   = \DB::table( 'photo_albums' )->select( 'id' )->get()->pluck( 'id' );

    foreach ( $arr as $key => $item )
    {

      if ( $item->photoalbum_id && ! $albums_ids->contains( $item->photoalbum_id ) )
      {
        $this->command->error( 'photo id : ' . $item->id . ', at date ' . ( $item->created_at ? $item->created_at : date( 'Y-m-d', $item->create_date ) ) . ', has wrong album id ' . $item->photoalbum_id . ', and url ' . $item->url );
      }
      try
      {
        $created_at = null;
        $updated_at = null;
        if ( $item->created_at === '0000-00-00 00:00:00' )
        {
          $created_at = now();
          $updated_at = now();
        } else
        {
          $created_at = $item->created_at ?? now();
          $updated_at = $item->updated_at ?? now();
        }

        \DB::table( 'photos' )->insert( [
                                          'id'             => $item->id,
                                          'photo_album_id' => $item->photoalbum_id ? ( $albums_ids->contains( $item->photoalbum_id ) ? $item->photoalbum_id : null ) : null,
                                          'news_id'        => $item->news_id ? ( $news_ids->contains( $item->news_id ) ? $item->news_id : null ) : null,
                                          'name'           => $item->name,
                                          'description'    => $item->description,
                                          'url'            => $item->url ?? '',
                                          'is_disabled'    => $item->is_disabled,
                                          'is_draft'       => ! $item->is_active,
                                          'no_of_views'    => $item->no_of_views,
                                          'created_by'     => $item->created_by ? $item->created_by : 1,
                                          'modified_by'    => $item->modified_by ? ( $users_ids->contains( $item->modified_by ) ? $item->modified_by : null ) : null,
                                          'created_at'     => $created_at,
                                          'updated_at'     => $updated_at,

                                        ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }
  }
}
