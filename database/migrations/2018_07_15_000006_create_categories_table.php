<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'categories', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'name' );
      $table->string( 'slug', 38 )->unique();
      $table->unsignedInteger( 'parent_id' )->nullable();
      $table->string( 'description' )->default( '' );
      $table->string( 'image' )->nullable();
      $table->string( 'color' )->default( '#000' );
      $table->boolean( 'is_active' )->default( 1 );
      $table->boolean( 'show_in_home' )->default( false );
      $table->unsignedInteger( 'created_by' )->nullable();
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->timestamps();

      $table->foreign( 'parent_id' )->references( 'id' )->on( 'categories' )->onDelete( 'RESTRICT' );
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
    Schema::drop( 'categories' );
  }

}
