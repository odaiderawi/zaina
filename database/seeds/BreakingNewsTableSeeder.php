<?php

use Illuminate\Database\Seeder;

class BreakingNewsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $arr = \DB::connection( 'mysql_old' )->table( 'breakingnews' )->get();
    \DB::table( 'breaking_news' )->truncate();

    foreach ( $arr as $key => $item )
    {
      try
      {
        \DB::table( 'breaking_news' )->insert( [
                                                 'id'           => $item->id,
                                                 'content'      => $item->content,
                                                 'is_active'    => $item->is_active,
                                                 'time_to_live' => $item->time_to_live,
                                                 'created_by'   => $item->created_by ? $item->created_by : 1,
                                                 'modified_by'  => $item->modified_by,
                                                 'deleted_at'   => @$item->deleted_at,
                                                 'created_at'   => $item->created_at,
                                                 'updated_at'   => $item->updated_at,
                                               ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }
  }
}
