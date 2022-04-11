<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'videos', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->unsignedInteger( 'category_id' )->nullable();
      $table->string( 'highlight_title' )->nullable();
      $table->string( 'slug', 38 )->unique();
      $table->string( 'name' );
      $table->string( 'image' );
      $table->text( 'description' );
      $table->string( 'source' )->nullable();
      $table->string( 'duration' )->nullable();
      $table->integer( 'no_of_views' )->default( 0 );
      $table->boolean( 'is_main' )->default( 0 );
      $table->boolean( 'is_disabled' )->default( 0 );
      $table->boolean( 'is_youtube' )->default( 1 );
      $table->string( 'url' )->nullable();
      $table->unsignedInteger( 'created_by' );
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->softDeletes();
      $table->timestamps();

      $table->foreign( 'category_id' )->references( 'id' )->on( 'categories' )->onDelete( 'RESTRICT' );
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
    Schema::drop( 'videos' );
  }

}
