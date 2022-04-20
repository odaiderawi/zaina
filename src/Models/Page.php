<?php

namespace Mezian\Zaina\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Mezian\Zaina\Traits\SluggableTrait;

class Page extends Model
{
  use SluggableTrait, SoftDeletes;

  protected $dates = [ 'deleted_at' ];

  protected $fillable = [ 'name', 'description', 'content', 'is_active', 'is_draft' ];

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

  public function getMorphClass()
  {
    return 'Page';
  }

  public function meta()
  {
    return $this->morphOne( Meta::class, 'metaable' );
  }

}
