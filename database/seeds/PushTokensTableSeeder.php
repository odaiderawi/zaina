<?php

use Illuminate\Database\Seeder;

class PushTokensTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $arr = [];
    try
    {
      $arr = \DB::connection( 'mysql_old' )->table( 'push_tokens' )->get();
    } catch ( \Illuminate\Database\QueryException $e )
    {
      $errorCode = $e->errorInfo[1];
      if ( $errorCode == 1146 )
      {
        $this->command->error( 'push_tokens table does not exist' );
        $this->command->error( '' );
      } else
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
      $this->command->info( 'Migrate old push tokens unsuccessfully' );

      return;
    }
    \DB::table( 'push_tokens' )->truncate();

    foreach ( $arr as $key => $item )
    {
      try
      {
        \DB::table( 'push_tokens' )->insert( [
                                               'id'         => $item->id,
                                               'token'      => $item->token,
                                               'device'     => @$item->device,
                                               'created_at' => $item->is_visible,
                                               'updated_at' => $item->is_visible,
                                             ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }
  }
}
