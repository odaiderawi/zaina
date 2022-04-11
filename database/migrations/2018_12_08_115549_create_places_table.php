<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'places', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'name' );
      $table->unsignedInteger( 'places_types_id' )->nullable();
      $table->string( 'identifier' );
      $table->enum( 'type', [ 'web', 'mobile', 'app' ] );
      $table->integer( 'width' );
      $table->integer( 'height' );
      $table->tinyInteger( 'active' )->default( 1 );
      $table->timestamps();
    } );
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists( 'places' );
  }
}
