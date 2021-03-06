<?php

namespace Mezian\Zaina\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class BreakingNews extends Model
{

  /**
   * Return the sluggable configuration array for this model.
   *
   * @return array
   *
   */

  protected $table = 'breaking_news';

  protected $fillable = [ 'content', 'is_active', 'time_to_live' ];

  protected static function boot()
  {
    parent::boot(); // TODO: Change the autogenerated stub

    self::creating( function ( $news ) {
      $news->created_by  = Auth::user()->id;
      $news->modified_by = Auth::user()->id;
    } );

    self::updated( function ( $news ) {
      $news->modified_by = Auth::user()->id;
    } );
  }

  public function scopeSearch( $q, $data )
  {
    if ( ! is_null( $data['title'] ) || $data['title'] != '' )
    {
      $q->where( 'title', 'LIKE', '%' . $data['title'] . '%' );
    }

    if ( ! is_null( $data['category_id'] ) || $data['category_id'] != '' )
    {
      $q->where( 'category_id', $data['category_id'] );
    }

    if ( ! is_null( $data['from_date'] ) || $data['from_date'] != '' )
    {
      $q->where( 'created_at', '>=', $this->parseDate( $data['from_date'] ) );
    }

    if ( ! is_null( $data['to_date'] ) || $data['to_date'] != '' )
    {
      $q->where( 'created_at', '<=', $this->parseDate( $data['to_date'] ) );
    }

    return $q;
  }

  public function parseDate( $value )
  {
    $date = new Carbon( substr( $value, 0, 24 ) );

    return Carbon::createFromFormat( 'Y-m-d H:i:s', $date )->format( 'Y-m-d H:i:s' );
  }
}
