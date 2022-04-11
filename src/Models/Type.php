<?php

namespace Mezian\Zaina\Models;

use Mezian\Zaina\Traits\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
  use SluggableTrait;

  /**
   * Return the sluggable configuration array for this model.
   *
   * @return array
   */
  public function sluggable()
  {
    return [
      'slug' => [
        'source' => 'name',
      ],
    ];
  }
}
