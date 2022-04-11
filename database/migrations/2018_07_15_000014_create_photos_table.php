<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhotosTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'photos', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->unsignedInteger( 'photo_album_id' )->nullable();
      $table->unsignedInteger( 'news_id' )->nullable();
      $table->string( 'name' )->nullable();
      $table->text( 'description' )->nullable();
      $table->string( 'url', 255 );
      $table->boolean( 'is_disabled' )->default( false );
      $table->boolean( 'is_draft' )->default( false );
      $table->integer( 'no_of_views' )->default( 0 );
      $table->unsignedInteger( 'created_by' );
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->timestamps();

      $table->foreign( 'photo_album_id' )->references( 'id' )->on( 'photo_albums' )->onDelete( 'SET NULL' );
      $table->foreign( 'news_id' )->references( 'id' )->on( 'news' )->onDelete( 'SET NULL' );
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
    Schema::drop( 'photos' );
  }

}
