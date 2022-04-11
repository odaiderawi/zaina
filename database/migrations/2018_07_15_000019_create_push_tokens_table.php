<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePushTokensTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'push_tokens', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'token' )->unique( 'token' );
      $table->string( 'device' )->nullable();
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
    Schema::drop( 'push_tokens' );
  }

}
