<?php

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $types = \DB::connection( 'mysql_old' )->table( 'types' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( 'types' )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    foreach ( $types as $key => $item )
    {
      try
      {
        \DB::table( 'types' )->insert( [
                                         'id'          => $item->id,
                                         'name'        => trim( $item->name ),
                                         'description' => $item->description,
                                       ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }
  }
}
