<?php

namespace Mezian\Zaina\Traits;

use Mezian\Zaina\Models\Taggable;

trait TaggableTrait
{
  public function getRelated()
  {
    $tagsIds    = $this->tags->pluck( 'id' );
    $relatedIds = Taggable::where( [
                                     [ 'taggable_type', '=', $this->getMorphClass() ],
                                     [ 'taggable_id', '!=', $this->id ],
                                   ] )->whereIn( 'tag_id', $tagsIds )->distinct()->pluck( 'taggable_id' );

    $related = get_class( $this )::whereIn( 'id', $relatedIds );

    return $related;
  }
}
