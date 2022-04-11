<?php

use Illuminate\Database\Seeder;

class PlacementsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $arr = \DB::connection( 'mysql_old' )->table( 'placements' )->get();
    \DB::table( 'placements' )->truncate();

    foreach ( $arr as $key => $item )
    {
      try
      {
        \DB::table( 'placements' )->insert( [
                                              'id'          => $item->id,
                                              'name'        => $item->name,
                                              'description' => $item->description,
                                              'is_visible'  => $item->is_visible,
                                            ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }
  }
}
