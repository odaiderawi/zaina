<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlacementsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'placements', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'name' );
      $table->string( 'description', 200 );
      $table->boolean( 'is_visible' );
    } );
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop( 'placements' );
  }

}
