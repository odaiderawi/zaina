<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $tableNames = config( 'permission.table_names' );

    $users = \DB::connection( 'mysql_old' )->table( 'users' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( 'users' )->truncate();
    \DB::table( $tableNames['model_has_roles'] )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );

    foreach ( $users as $key => $item )
    {
      try
      {
        \DB::table( 'users' )->insert( [
                                         'id'             => $item->id,
                                         'email'          => $item->email ?? $key,
                                         'username'       => $item->username ?? $key,
                                         'password'       => $item->password ?? '.',
                                         'first_name'     => $item->first_name ? $item->first_name : '...',
                                         'last_name'      => $item->last_name ? $item->last_name : '...',
                                         'display_name'   => $item->display_name ? $item->display_name : $item->first_name . ' ' . $item->last_name,
                                         'mobile'         => $item->mobile,
                                         'facebook'       => $item->facebook,
                                         'twitter'        => $item->twitter,
                                         'image'          => $item->image ? $item->image : 'user.png',
                                         'description'    => $item->description,
                                         'address'        => $item->address,
                                         'is_disable'     => $item->is_active ? ! $item->is_active : $item->is_active,
                                         'created_by'     => $item->created_by,
                                         'modified_by'    => $item->modified_by,
                                         'remember_token' => str_random( 10 ),
                                         'deleted_at'     => null,
                                         'created_at'     => $item->created_at,
                                         'updated_at'     => $item->updated_at,
                                       ] );

        \DB::table( $tableNames['model_has_roles'] )->insert( [
                                                                'role_id'    => $item->type_id ? $item->type_id : 3,
                                                                'model_type' => 'Mezian\Zaina\App\Models\User',
                                                                'model_id'   => $item->id,
                                                              ] );

      } catch ( \Illuminate\Database\QueryException $e )
      {
        $this->command->error( $e->getMessage() );
        $this->command->error( '' );
      }
    }
//        factory(App\User::class, 5)->create()->each(function ($u) {
//            $u->assignRole(\Spatie\Permission\Models\Role::first());
//        });

    $users_privileges = \DB::connection( 'mysql_old' )->table( 'users_privileges' )->get();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    \DB::table( $tableNames['model_has_permissions'] )->truncate();
    \DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

    $count = 0;
    foreach ( $users_privileges as $key => $item )
    {
      $user = \Mezian\Zaina\App\Models\User::find( $item->user_id );
      if ( ! $user || $user->getPermissionsViaRoles()->where( 'id', $item->privilege_id )->first() )
      {
        continue;
      }

      try
      {
        \DB::table( $tableNames['model_has_permissions'] )->insert( [
                                                                      'permission_id' => @$item->privilege_id,
                                                                      'model_type'    => 'Mezian\Zaina\App\Models\User',
                                                                      'model_id'      => @$item->user_id,
                                                                    ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $count ++;
        $errorCode = $e->errorInfo[1];
        if ( $errorCode == 1062 )
        {
          $this->command->warn( $count . ' - Duplicated, user id : ' . $item->user_id . ' , permission id : ' . $item->privilege_id );
        } else if ( $errorCode == 1452 )
        {
          $this->command->warn( $count . ' - Foreign key does not exist, user id : ' . $item->user_id . ' , permission id : ' . $item->privilege_id );
        } else
        {
          $this->command->error( $e->getMessage() );
          $this->command->error( '' );
        }
      }
    }

    $extraprivileges = \DB::connection( 'mysql_old' )->table( 'extraprivileges' )->get();

    $count = 0;
    foreach ( $extraprivileges as $key => $item )
    {
      $user = \Mezian\Zaina\App\Models\User::find( $item->user_id );
      if ( ! $user || $user->getPermissionsViaRoles()->where( 'id', $item->privilege_id )->first() )
      {
        continue;
      }

      try
      {
        \DB::table( $tableNames['model_has_permissions'] )->insert( [
                                                                      'permission_id' => $item->privilege_id,
                                                                      'model_type'    => 'Mezian\Zaina\App\Models\User',
                                                                      'model_id'      => $item->user_id,
                                                                    ] );
      } catch ( \Illuminate\Database\QueryException $e )
      {
        $count ++;
        $errorCode = $e->errorInfo[1];
        if ( $errorCode == 1062 )
        {
          $this->command->warn( $count . ' - Duplicated user id : ' . $item->user_id . ' , permission id : ' . $item->privilege_id );
        } else if ( $errorCode == 1452 )
        {
          $this->command->warn( $count . ' - Foreign key does not exist, user id : ' . $item->user_id . ' , permission id : ' . $item->privilege_id );
        } else
        {
          $this->command->error( $e->getMessage() );
          $this->command->error( '' );
        }
      }
    }

  }
}
