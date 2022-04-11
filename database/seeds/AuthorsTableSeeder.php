<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $authors = \DB::connection( 'mysql_old' )->table( 'authors' )->where( 'name', '!=', null )->get();
    $users   = \DB::connection( 'mysql_old' )->table( 'users' )->where( 'email', null )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( 'authors' )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    foreach ( $authors as $key => $item )
    {
      $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Author::class, 'slug', $item->name );
      if ( mb_strlen( $slug ) > 38 )
      {
        $diff = substr( $slug, 0, 37 );
        $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Author::class, 'slug', $item->name, [ 'maxLength' => strlen( $diff ) - 1 ] );
      }
      try
      {
        \DB::table( 'authors' )->insert( [
                                           'id'   => $item->id,
                                           'name' => trim( $item->name ),
                                           'slug' => $slug,
                                         ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }

    foreach ( $users as $key => $item )
    {
      $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Author::class, 'slug', $item->display_name );
      if ( mb_strlen( $slug ) > 38 )
      {
        $diff = substr( $slug, 0, 37 );
        $slug = SlugService::createSlug( Mezian\Zaina\App\Models\Author::class, 'slug', $item->display_name, [ 'maxLength' => strlen( $diff ) - 1 ] );
      }
      try
      {
        \DB::table( 'authors' )->insert( [
                                           'id'   => $item->id,
                                           'name' => trim( $item->display_name ),
                                           'slug' => $slug,
                                         ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }

  }
}
