<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggablesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'taggables', function ( Blueprint $table ) {
      $table->unsignedInteger( 'tag_id' );
      $table->nullableMorphs( 'taggable' );
      $table->primary( [ 'tag_id', 'taggable_type', 'taggable_id' ] );

      $table->foreign( 'tag_id' )->references( 'id' )->on( 'tags' )->onDelete( 'CASCADE' );
    } );
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists( 'taggables' );
  }
}
