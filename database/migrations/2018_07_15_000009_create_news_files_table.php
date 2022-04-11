<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsFilesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'news_files', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'image' );
      $table->string( 'title' );
      $table->string( 'slug', 38 )->unique();
      $table->boolean( 'is_main' )->default( true );
      $table->boolean( 'is_active' )->default( true );
      $table->unsignedInteger( 'created_by' );
      $table->unsignedInteger( 'modified_by' )->nullable();
      $table->softDeletes();
      $table->timestamps();

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
    Schema::drop( 'news_files' );
  }

}
