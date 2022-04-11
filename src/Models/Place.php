<?php

namespace Mezian\Zaina\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{

  protected $fillable = [ 'name', 'identifier', 'type', 'width', 'height', 'active' ];

  public function ad()
  {
    return $this->hasOne( Ad::class );
  }

}
