<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'settings', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'key' )->unique();
      $table->string( 'name' );
      $table->string( 'description' )->nullable();
      $table->text( 'value' )->nullable();
      $table->text( 'field' );
      $table->tinyInteger( 'active' );
      $table->timestamps();
      $table->unsignedInteger( 'created_by' )->nullable();
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->softDeletes();

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
    Schema::drop( 'settings' );
  }

}
