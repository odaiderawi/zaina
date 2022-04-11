<?php

namespace Mezian\Zaina\Models;

use Mezian\Zaina\Traits\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
  use SluggableTrait;

  public $timestamps = false;

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

  public static function author( $model, $data )
  {
    foreach ( $data as $author )
    {
      if ( is_numeric( $author['id'] ) )
      {
        $old_author = Author::findOrFail( $author['id'] );
        $authors    = $old_author;
      } else if ( ( $authorr = Author::where( 'name', $author['name'] )->first() ) != null )
      {
        $authors = $authorr;
      } else
      {
        $new_author       = new Author();
        $new_author->name = $author['name'];
        $new_author->save();

        $authors = $new_author;

      }
      $model->update( [ 'author_id' => $authors->id ] );
    }

  }

  public function articles()
  {
    return $this->hasMany( Article::class, 'author_id' );
  }
}
