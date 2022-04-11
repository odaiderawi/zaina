<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'news', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'title', 500 )->index();
      $table->string( 'slug', 38 )->unique();
      $table->integer( 'no_of_views' )->default( 0 )->index();
      $table->unsignedInteger( 'type_id' )->nullable();
      $table->unsignedInteger( 'category_id' )->index();
      $table->unsignedInteger( 'news_file_id' )->nullable();
      $table->unsignedInteger( 'photo_album_id' )->nullable();
      $table->unsignedInteger( 'playlist_id' )->nullable();
      $table->string( 'video' )->nullable();
//            $table->unsignedInteger('video_id')->nullable();
//            $table->boolean('is_notify')->default(false);
//            $table->boolean('is_notified')->nullable()->default(false);
      $table->string( 'image' );
      $table->string( 'image_description' )->nullable();
      $table->string( 'highlight_title' )->nullable();
      $table->string( 'source' )->nullable();
      $table->longText( 'content' );
      $table->text( 'summary' )->nullable();
      $table->boolean( 'is_news_ticker' )->default( false );
      $table->boolean( 'is_main_news' )->default( false )->index();
      $table->boolean( 'is_special_news' )->default( false )->index();
      $table->boolean( 'is_particular_news' )->default( false );
      $table->boolean( 'is_shown_in_template' )->default( false );
      $table->boolean( 'is_share_to_facebook' )->default( false );
      $table->boolean( 'is_share_to_twitter' )->default( false );
      $table->boolean( 'is_draft' )->default( true )->index();
      $table->boolean( 'is_archived' )->default( false );
      $table->boolean( 'is_no_index' )->default( false );
      $table->boolean( 'use_watermark' )->default( false );
      $table->boolean( 'is_disabled' )->default( false );
      $table->unsignedInteger( 'created_by' );
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->dateTime( 'date_to_publish' )->nullable();
      $table->softDeletes();
      $table->timestamps();

      $table->foreign( 'type_id' )->references( 'id' )->on( 'types' )->onDelete( 'SET NULL' );
      $table->foreign( 'category_id' )->references( 'id' )->on( 'categories' )->onDelete( 'RESTRICT' );
      $table->foreign( 'news_file_id' )->references( 'id' )->on( 'news_files' )->onDelete( 'SET NULL' );
      $table->foreign( 'photo_album_id' )->references( 'id' )->on( 'photo_albums' )->onDelete( 'SET NULL' );
      $table->foreign( 'playlist_id' )->references( 'id' )->on( 'playlists' )->onDelete( 'SET NULL' );
//            $table->foreign('video_id')->references('id')->on('videos')->onDelete('SET NULL');
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
    Schema::drop( 'news' );
  }

}
