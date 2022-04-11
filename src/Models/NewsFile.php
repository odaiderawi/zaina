<?php

namespace Mezian\Zaina\Models;

use Mezian\Zaina\Traits\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class NewsFile extends Model
{
  use SluggableTrait;

  protected $fillable = [];

  /**
   * Return the sluggable configuration array for this model.
   *
   * @return array
   */
  public function sluggable()
  {
    return [
      'slug' => [
        'source' => 'title',
      ],
    ];
  }

  public function getMorphClass()
  {
    return 'NewsFile';

  }

  public function meta()
  {
    return $this->morphOne( Meta::class, 'metaable' );
  }

}
