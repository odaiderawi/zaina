<?php

use Illuminate\Database\Seeder;

class PermissionsTablesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $tableNames = config( 'permission.table_names' );

    $usertypes = \DB::connection( 'mysql_old' )->table( 'usertypes' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( $tableNames['roles'] )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    foreach ( $usertypes as $key => $item )
    {
      try
      {
        \DB::table( $tableNames['roles'] )->insert( [
                                                      'id'          => $item->id,
                                                      'name'        => $item->type,
                                                      'guard_name'  => 'admin',
                                                      'description' => $item->description,
                                                      'created_at'  => $item->created_at,
                                                      'updated_at'  => $item->updated_at,
                                                    ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }

    $privileges = \DB::connection( 'mysql_old' )->table( 'privileges' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( $tableNames['permissions'] )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    foreach ( $privileges as $key => $item )
    {
      try
      {
        \DB::table( $tableNames['permissions'] )->insert( [
                                                            'id'          => $item->id,
                                                            'name'        => $item->name,
                                                            'guard_name'  => 'admin',
                                                            'description' => $item->description,
                                                            'created_at'  => $item->created_at,
                                                            'updated_at'  => $item->updated_at,
                                                          ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }

    $users_privileges = \DB::connection( 'mysql_old' )->table( 'privilege_usertype' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( $tableNames['role_has_permissions'] )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    foreach ( $users_privileges as $key => $item )
    {
      try
      {
        \DB::table( $tableNames['role_has_permissions'] )->insert( [
                                                                     'permission_id' => $item->privilege_id,
                                                                     'role_id'       => $item->usertype_id,
                                                                   ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }

  }
}
