<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'files', function ( Blueprint $table ) {
      $table->integer( 'id', true );
      $table->string( 'name' );
      $table->string( 'description' )->nullable();
      $table->string( 'url' );
      $table->string( 'type' );
      $table->boolean( 'is_draft' )->default( false );
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
    Schema::drop( 'files' );
  }

}
