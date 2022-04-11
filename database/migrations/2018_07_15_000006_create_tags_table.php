<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'tags', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'name' )->unique();
      $table->string( 'slug', 38 )->unique();
      $table->string( 'description', 200 )->nullable();
      $table->integer( 'no_of_uses' )->default( 0 );
      $table->boolean( 'is_disabled' )->default( false );
      $table->boolean( 'is_index' )->nullable()->default( true );
      $table->boolean( 'is_follow' )->nullable()->default( true );
      $table->unsignedInteger( 'created_by' )->nullable();
      $table->unsignedInteger( 'modified_by' )->nullable();
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
    Schema::drop( 'tags' );
  }

}
