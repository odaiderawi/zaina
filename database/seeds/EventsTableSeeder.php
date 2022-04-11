<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $arr = \DB::connection( 'mysql_old' )->table( 'events' )->get();
    \DB::table( 'events' )->truncate();

    foreach ( $arr as $key => $item )
    {
      try
      {
        \DB::table( 'events' )->insert( [
                                          'id'             => $item->id,
                                          'title'          => $item->title,
                                          'time_to_live'   => $item->time_to_live,
                                          'eventable_type' => $item->eventable_type,
                                          'eventable_id'   => $item->eventable_id,
                                          'created_by'     => $item->created_by ? $item->created_by : 1,
                                          'modified_by'    => $item->modified_by,
                                          'deleted_at'     => @$item->deleted_at,
                                          'created_at'     => $item->created_at,
                                          'updated_at'     => $item->updated_at,
                                        ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }
  }
}
