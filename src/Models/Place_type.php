<?php

namespace Mezian\Zaina\Models;

use Illuminate\Database\Eloquent\Model;

class Place_type extends Model
{
  protected $table = 'places_types';

  protected $fillable = [ 'name', 'active' ];

}
