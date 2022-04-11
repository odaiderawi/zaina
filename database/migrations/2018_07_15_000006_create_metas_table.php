<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create( 'metas', function ( Blueprint $table ) {
      $table->string( 'slug', 38 );
      $table->string( 'seo_title', 300 );
      $table->string( 'seo_description', 300 );
      $table->boolean( 'is_amp' )->nullable()->default( false );
      $table->string( 'social_title', 300 );
      $table->string( 'social_description', 300 );
      $table->string( 'social_image' )->nullable();
      $table->boolean( 'is_instant_article' )->nullable()->default( false );
      $table->boolean( 'is_index' )->nullable()->default( true );
      $table->boolean( 'is_follow' )->nullable()->default( true );
      $table->nullableMorphs( 'metaable' );

      $table->unique( [ 'metaable_type', 'metaable_id' ] );
    } );
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists( 'metas' );
  }
}
