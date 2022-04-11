<?php

namespace Mezian\Zaina\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Taggable extends Pivot
{
  protected $table = 'taggables';
}
