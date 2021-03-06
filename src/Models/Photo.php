<?php

namespace Mezian\Zaina\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Photo extends Model
{

  protected $fillable = [ 'url', 'description', 'name', 'photo_album_id', 'news_id' ];

  protected static function boot()
  {
    parent::boot(); // TODO: Change the autogenerated stub

    self::creating( function ( $photo ) {
      $photo->created_by  = Auth::user()->id;
      $photo->modified_by = Auth::user()->id;
    } );

    self::updated( function ( $photo ) {
      $photo->modified_by = Auth::user()->id;
    } );
  }

  public function album()
  {
    return $this->belongsTo( PhotoAlbum::class, 'photo_album_id' );

  }

}
