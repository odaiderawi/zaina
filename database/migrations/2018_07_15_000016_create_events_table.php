<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'events', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'title', 500 );
      $table->integer( 'time_to_live' )->default( 60 );
      $table->nullableMorphs( 'eventable' );
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
    Schema::drop( 'events' );
  }

}
