<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhotoAlbumsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'photo_albums', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'highlight_title' )->nullable();
      $table->unsignedInteger( 'category_id' )->nullable();
      $table->unsignedInteger( 'news_file_id' )->nullable();
      $table->string( 'name' )->unique();
      $table->string( 'slug', 38 )->unique();
      $table->text( 'description' )->nullable();
      $table->string( 'cover_photo' )->nullable();
      $table->integer( 'no_of_views' )->default( 0 );
      $table->boolean( 'is_main' )->default( false );
      $table->boolean( 'is_active' )->default( true );

      $table->boolean( 'use_watermark' )->default( false );
      $table->integer( 'sort' )->unsigned();
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
    Schema::drop( 'photo_albums' );
  }

}
