<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'contacts', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'name', 100 );
      $table->string( 'email', 100 );
      $table->string( 'message' );
      $table->string( 'type', 100 )->nullable();
      $table->boolean( 'seen' )->default( false );
      $table->softDeletes();
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
    Schema::drop( 'contacts' );
  }

}
