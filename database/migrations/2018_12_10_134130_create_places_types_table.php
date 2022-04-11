<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTypesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'places_types', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'name' );
      $table->tinyInteger( 'active' )->default( 1 );
      $table->timestamps();
    } );

    DB::table( 'places_types' )->insert(
      [
        'name'   => 'web',
        'active' => 1,
      ],
      [
        'name'   => 'mobile',
        'active' => 1,
      ],
      [
        'name'   => 'app',
        'active' => 1,
      ]
    );
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists( 'places_types' );
  }
}
