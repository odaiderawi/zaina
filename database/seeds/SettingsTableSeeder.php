<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SettingsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $settings = \DB::connection( 'mysql_old' )->table( 'settings' )->get();
    \DB::table( 'settings' )->truncate();

    $columns = Schema::connection( 'mysql_old' )->getColumnListing( 'settings' );
    $columns = array_diff( $columns, [ 'created_at', 'updated_at', 'created_by', 'modified_by' ] );

    foreach ( $settings as $key => $item )
    {
      foreach ( $columns as $column )
      {
        \DB::table( 'settings' )->insert( [
                                            'key'         => $column,
                                            'name'        => ucwords( str_replace( "_", " ", $column ) ),
                                            'description' => '',
                                            'value'       => $item->{$column},
                                            'field'       => json_encode( [
                                                                            'name'  => 'value',
                                                                            'label' => ucwords( str_replace( "_", " ", $column ) ),
                                                                            'type'  => 'text',
                                                                          ] ),
                                            'active'      => true,
                                          ] );
      }
    }
  }
}
