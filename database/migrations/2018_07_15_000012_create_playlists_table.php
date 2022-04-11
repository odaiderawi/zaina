<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlaylistsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'playlists', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->unsignedInteger( 'category_id' );
      $table->unsignedInteger( 'news_file_id' )->nullable();
      $table->string( 'name' )->unique();
      $table->string( 'slug', 38 )->unique();
      $table->string( 'description' )->nullable();
      $table->boolean( 'is_main' )->default( false );
      $table->unsignedInteger( 'created_by' );
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->softDeletes();
      $table->timestamps();

      $table->foreign( 'category_id' )->references( 'id' )->on( 'categories' )->onDelete( 'RESTRICT' );
      $table->foreign( 'news_file_id' )->references( 'id' )->on( 'news_files' )->onDelete( 'SET NULL' );
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
    Schema::drop( 'playlists' );
  }

}
