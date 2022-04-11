<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLiveBroadcastsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'live_broadcasts', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'title' );
      $table->string( 'type' );
      $table->string( 'url' );
      $table->boolean( 'is_active' );

      $table->unsignedInteger( 'created_by' );
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->softDeletes();
      $table->timestamps();

      $table->foreign( 'created_by' )->references( 'id' )->on( 'users' );
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
    Schema::drop( 'live_broadcasts' );
  }

}
