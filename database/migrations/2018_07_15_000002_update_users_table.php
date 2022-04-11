<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateUsersTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::drop( 'users' );

    Schema::create( 'users', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'email' )->unique();
      $table->string( 'username' )->unique();
      $table->string( 'password' );
      $table->string( 'first_name' );
      $table->string( 'last_name' );
      $table->string( 'display_name' );
      $table->string( 'mobile' )->nullable();
      $table->string( 'facebook' )->nullable();
      $table->string( 'twitter' )->nullable();
      $table->string( 'image' )->default( 'user.png' );
      $table->string( 'description' )->nullable();
      $table->string( 'address' )->nullable();
      $table->boolean( 'is_disable' )->default( false );
      $table->unsignedInteger( 'created_by' )->nullable();
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->rememberToken();
      $table->softDeletes();
      $table->timestamps();

      $table->foreign( 'created_by' )->references( 'id' )->on( 'users' )->onDelete( 'set null' );
      $table->foreign( 'modified_by' )->references( 'id' )->on( 'users' )->onDelete( 'set null' );
    } );
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop( 'users' );
  }

}
