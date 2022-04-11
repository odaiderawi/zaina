<?php

namespace Mezian\Zaina\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{

  protected $fillable = [ 'name', 'provider', 'type', 'place_id', 'url', 'image', 'active' ];

  public function place()
  {
    return $this->belongsTo( Place::class );
  }

}
