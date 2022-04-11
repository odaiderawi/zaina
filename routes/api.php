<?php

use Illuminate\Support\Facades\Route;

Route::prefix( 'api' )
     ->middleware( 'web' )
     ->namespace( 'Mezian\Zaina\Http\Controllers\Api' )->group( function () {

    Route::ApiResource( 'videos', 'VideoController' )->only( [
                                                               'index',
                                                               'show',
                                                             ] );

    Route::ApiResource( 'news', 'NewsController' )->only( [
                                                            'index',
                                                            'show',
                                                          ] );

    Route::ApiResource( 'articles', 'ArticleController' )->only( [
                                                                   'index',
                                                                   'show',
                                                                 ] );

    Route::ApiResource( 'albums', 'AlbumController' )->only( [
                                                               'index',
                                                               'show',
                                                             ] );

    Route::get( 'categories', 'CategoryController@index' );

    Route::get( 'settings', 'SettingController@index' );

  } );