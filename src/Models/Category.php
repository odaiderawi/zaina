<?php

namespace Mezian\Zaina\Models;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Mezian\Zaina\Traits\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use SluggableTrait;
  use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

  protected $fillable = [
    'name',
    'parent_id',
    'description',
    'image',
    'color',
    'is_active',
    'show_in_home',
    'sort',
    'show_in_nav',
    'is_file',
    'is_english',
  ];

  protected $with    = [ 'children', 'metas', 'publisher' ];
  protected $appends = [ 'url', 'is_used' ];

  protected static function boot()
  {
    parent::boot(); // TODO: Change the autogenerated stub

    self::creating( function ( $category ) {
      $category->created_by  = Auth::user() ? Auth::user()->id : 2;
      $category->modified_by = Auth::user() ? Auth::user()->id : 2;
    } );

    self::created( function ( $category ) {
      Meta::data( $category, request()->all() );
    } );

    self::updated( function ( $category ) {
      $category->modified_by = Auth::user()->id;
    } );
  }

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
    return 'Category';

  }

  public function getUrlAttribute()
  {
    if ( $this->is_file && is_null( $this->parent_id ) )
    {
      return route( 'category.show.file', [ 'slug' => $this->id . '-' . $this->slug ] );
    }

    return route( 'category.show', [ 'slug' => $this->id . '-' . $this->slug ] );
  }

  public function children()
  {
    return $this->hasMany( Category::class, 'parent_id' );
  }

  public function parent()
  {
    return $this->belongsTo( Category::class, 'parent_id' );
  }

  public function metas()
  {
    return $this->morphOne( Meta::class, 'metaable' );
  }

  public function scopePlaylists( $q )
  {
    return $q->where( 'parent_id', 555 )->orderBy( 'created_at', 'DESC' );

  }

  public function publisher()
  {
    return $this->belongsTo( User::class, 'created_by' );

  }

  public function videos()
  {
    return $this->hasMany( Video::class, 'category_id' );
  }

  public function news()
  {
    return $this->hasMany( News::class, 'category_id' );
  }

  public function articles()
  {
    return $this->hasMany( Article::class, 'category_id' );
  }

  public function delete()
  {
    if ( $usage = $this->isUsed() )
    {
      return $usage;
    }

    return parent::delete();
  }

  /**
   * Determine if the user is linked to any relation
   *
   * @return array|bool
   */
  public function isUsed()
  {
    $usage = [];

    // used in sales orders
    $this->loadCount( 'news' );
    $this->loadCount( 'children' );
    $this->loadCount( 'videos' );
    $this->loadCount( 'articles' );
    if ( $this->news_count )
    {
      $usage['news'] = trans_choice( 'messages.currently_used', $this->news_count, [
        'resource' => 'التصنيف',
        'model'    => 'category(' . $this->name . ')',
      ] );
    }

    if ( $this->children_count )
    {
      $usage['children'] = trans_choice( 'messages.currently_used', $this->children_count, [
        'resource' => 'التصنيف',
        'model'    => 'category(' . $this->name . ')',
      ] );
    }

    if ( $this->videos_count )
    {
      $usage['videos'] = trans_choice( 'messages.currently_used', $this->videos_count, [
        'resource' => 'التصنيف',
        'model'    => 'category(' . $this->name . ')',
      ] );
    }

    if ( $this->articles_count )
    {
      $usage['articles'] = trans_choice( 'messages.currently_used', $this->articles_count, [
        'resource' => 'التصنيف',
        'model'    => 'category(' . $this->name . ')',
      ] );
    }

    return count( $usage ) ? $usage : false;
  }

  public function getIsUsedAttribute()
  {
    $reasons = $this->isUsed();
    if ( request()->route()->getName() === 'categories.all' )
    {
      if ( is_array( $reasons ) and count( $reasons ) )
      {
        return true;
      }

    }

    return false;
  }

}
