<?php

use Illuminate\Support\Facades\Schema;
use Mezian\Zaina\Models\Place;
use Mezian\Zaina\Models\Setting;

if ( ! function_exists( 'zaina_image' ) )
{
  function zaina_image( $path, $size = null )
  {
    if ( starts_with( $path, 'uploads/' ) )
    {
      if ( $size )
      {
//                return '/storage/' . $size . '/' . $path;
        return 'https://api.al-aalem.com/api/555x323/uploads/uploadCenter/2021/03/1615251748jpvpr.jpg';
      }

      return '/storage/' . $path;
    } else
    {
      if ( file_exists( $path ) )
      {
        return $path;
      } else
      {
        return asset( 'img/news1.jpg' );
      }
    }
  }
}

if ( ! function_exists( 'settings' ) )
{
  function settings( $key )
  {
    if ( Schema::hasTable( 'settings' ) )
    {
      return Setting::where( 'key', $key )->first() ? Setting::where( 'key', $key )->first()->value : "...";

    } else
    {
      return '...';
    }
  }
}

if ( ! function_exists( 'getPlace' ) )
{
  function getPlace( $key, $type = null )
  {
    if ( Schema::hasTable( 'places' ) )
    {
      return Place::where( 'identifier', $key )
                  ->where( 'type', $type )
                  ->with( 'ad' )
                  ->first();
    } else
    {
      return null;
    }

  }
}
