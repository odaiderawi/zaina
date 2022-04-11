<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'articles', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'title', 500 );
      $table->unsignedInteger( 'author_id' )->nullable();
      $table->string( 'slug', 38 )->unique();
      $table->integer( 'no_of_views' )->default( 0 );
      $table->unsignedInteger( 'category_id' )->nullable();
      $table->string( 'image' );
      $table->string( 'image_description' )->nullable();
      $table->string( 'highlight_title' )->nullable();
      $table->string( 'source' )->nullable();
      $table->longText( 'content' );
      $table->text( 'summary' )->nullable();
      $table->boolean( 'is_article_ticker' )->default( false );
      $table->boolean( 'is_main_article' )->default( false );
      $table->boolean( 'is_special_article' )->default( false );
      $table->boolean( 'is_particular_article' )->default( false );
      $table->boolean( 'is_shown_in_template' )->default( false );
      $table->boolean( 'is_share_to_facebook' )->default( 0 );
      $table->boolean( 'is_share_to_twitter' )->default( 0 );
      $table->boolean( 'is_draft' )->default( true );
      $table->boolean( 'is_archived' )->default( false );
      $table->boolean( 'use_watermark' )->default( false );
      $table->boolean( 'is_disabled' )->default( false );
      $table->unsignedInteger( 'created_by' )->nullable();
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->dateTime( 'date_to_publish' )->nullable();
      $table->softDeletes();
      $table->timestamps();

      $table->foreign( 'author_id' )->references( 'id' )->on( 'authors' )->onDelete( 'RESTRICT' );
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
    Schema::drop( 'articles' );
  }

}
