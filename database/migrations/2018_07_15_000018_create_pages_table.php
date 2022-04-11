<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'pages', function ( Blueprint $table ) {
      $table->increments( 'id' );
      $table->string( 'name' );
      $table->string( 'slug', 38 )->unique();
      $table->string( 'description', 500 );
      $table->text( 'content' );
      $table->boolean( 'is_active' )->default( false );
      $table->boolean( 'is_draft' )->default( true );
      $table->unsignedInteger( 'created_by' )->nullable();
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
    Schema::drop( 'pages' );
  }

}
