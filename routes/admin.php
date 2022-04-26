<?php

use Illuminate\Support\Facades\Route;
use Mezian\Zaina\Http\Middleware\LogOutDisabledUsers;

Route::prefix( 'admin' )->namespace( 'Mezian\Zaina\Http\Controllers\Admin' )->group( function () {

  Route::post( 'login', 'UserController@login' );

  Route::middleware( [ 'auth:api', LogOutDisabledUsers::class ] )->group( function () {

    Route::get( 'statistics', 'StatisticsController@statistics' );
    Route::get( 'fetchMostVisitedPages/{period}', 'StatisticsController@fetchMostVisitedPages' );

    Route::apiResource( 'news', 'NewsController' );
    Route::prefix( 'news' )->group( function () {
      Route::get( 'status/{id}', 'NewsController@status' );
      Route::get( 'last-news/{number}', 'NewsController@last_news' );
      Route::post( 'draft', 'NewsController@draft' );
      Route::post( 'search', 'NewsController@search' );
    } );
    Route::get( 'types', 'TypeController@index' );

    Route::get( 'deleted', 'NewsController@deleted' );
    Route::get( 'restore/{id}', 'NewsController@restore' );

    Route::apiResource( 'articles', 'ArticleController' );
    Route::prefix( 'articles' )->group( function () {
      Route::get( 'status/{id}', 'ArticleController@status' );
      Route::get( 'last-articles/{number}', 'ArticleController@last_articles' );
      Route::post( 'draft', 'ArticleController@draft' );
      Route::post( 'search', 'ArticleController@search' );
    } );

    Route::apiResource( 'videos', 'VideoController' );
    Route::prefix( 'video' )->group( function () {
      Route::get( 'status/{id}', 'VideoController@status' );
      Route::get( 'last-videos/{number}', 'VideoController@last_videos' );
      Route::post( 'search', 'VideoController@search' );
      Route::get( 'play-lists', 'VideoController@playLists' );
      Route::get( 'playlist/{id}', 'VideoController@videosByPlaylist' );

    } );

    Route::apiResource( 'albums', 'AlbumsController' );
    Route::prefix( 'album' )->group( function () {
      Route::get( 'status/{id}', 'AlbumsController@status' );
      Route::post( 'search', 'AlbumsController@search' );

    } );

    Route::apiResource( 'category', 'CategoryController' );
    Route::prefix( 'categories' )->group( function () {
      Route::get( 'status/{id}', 'CategoryController@status' );
      Route::get( 'show-in-home/{id}', 'CategoryController@showInHome' );
      Route::get( 'show-in-nav/{id}', 'CategoryController@showInNav' );
      Route::get( 'parents', 'CategoryController@parents' );
      Route::get( 'sorts', 'CategoryController@getAvailableSorts' );
      Route::get( 'get-categories', 'CategoryController@getCategories' )->name( 'categories.all' );
    } );

    Route::apiResource( 'tags', 'TagController' );
    Route::get( 'search-tags/{id}', 'TagController@search_tags' );

    Route::apiResource( 'pages', 'PageController' );
    Route::get( 'status/{id}', 'PageController@status' );

    Route::apiResource( 'authors', 'AuthorController' );
    Route::get( '/search-authors/{id}', 'AuthorController@search_authors' );

    Route::apiResource( 'breaking-news', 'BreakingNewsController' );

    Route::prefix( 'settings' )->group( function () {
      Route::get( '/', 'SettingController@index' );
      Route::post( 'update', 'SettingController@update' );
    } );

    Route::apiResource( 'live-broadcasts', 'LiveBroadcastController' );

    Route::prefix( 'statistics' )->group( function () {
      Route::get( 'editors/{period}', 'StatisticsController@editors' );
    } );

    Route::prefix( 'places' )->group( function () {
      Route::get( '/', 'PlaceController@index' );
      Route::get( 'free-places', 'PlaceController@getFreePlaces' );
      Route::post( 'store', 'PlaceController@store' );
      Route::put( '{id}', 'PlaceController@update' );
      Route::delete( 'destroy', 'PlaceController@destroy' );
      Route::get( 'types', 'PlaceController@places_types' );
      Route::get( 'placements', 'PlaceController@placements' );
      Route::get( '{id}', 'PlaceController@show' );
    } );

    Route::post( 'uploadFiles', 'FileController@uploadFiles' );

    Route::get( '/getAllImages/{year}/{month}/{count}/{type?}', 'FileController@getAllImages' );
    Route::get( '/getFileById/{id}', 'FileController@getFileById' );

    Route::get( '/user', 'UserController@index' );
    Route::get( '/user/types', 'UserController@types' );
    Route::get( '/user/{id}', 'UserController@show' );

    Route::prefix( 'user' )->group( function () {
      Route::post( 'register', 'UserController@register' );
      Route::post( 'changePassword', 'UserController@changePassword' );
      Route::PUT( 'update/{id}', 'UserController@update' );
      Route::get( 'disable/{id}', 'UserController@disable' );
    } );

    Route::apiResource( 'ads', 'AdController' );

  } );

} );

Route::get( 'password', function () {
  dd( bcrypt( '123456' ) );
} );

Route::get( 'settings', function () {
  return \Mezian\Zaina\Models\Setting::pluck( 'value', 'key' )->toJson();
} );

Route::namespace( 'Mezian\Zaina\Http\Controllers\Admin' )->group( function () {
  Route::get( '/storage/{size?}/{image?}', 'FileController@resize_images' )->where( 'image', '(.*)' );
} );
