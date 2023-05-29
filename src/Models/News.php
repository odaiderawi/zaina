<?php

namespace Mezian\Zaina\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mezian\Zaina\Traits\SluggableTrait;
use Mezian\Zaina\Traits\TaggableTrait;

use Toolkito\Larasap\SendTo;

class News extends Model
{
  use SluggableTrait, TaggableTrait, SoftDeletes;
  use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

  public $domain;

  protected $dates = [ 'deleted_at', 'date_to_publish' ];

  protected $with    = [ 'tags', 'photos', 'publisher', 'category', 'metas', 'type' ];
  protected $appends = [ 'url' ];

//    protected $attributes = [
//        'title' => 'draft',
//        'content' => 'draft',
//        'image' => 'draft',
//        'category_id' => '0'
//    ];

  public function __construct( array $attributes = [] )
  {
    parent::__construct( $attributes );

    $this->domain = env( 'APP_URL' ) . '/news';
  }

  protected $fillable = [
    'title',
    'image',
    'content',
    'category_id',
    'video',
    'date_to_publish',
    'is_news_ticker',
    'is_main_news',
    'is_special_news',
    'is_particular_news',
    'is_shown_in_template',
    'is_share_to_facebook',
    'is_share_to_twitter',
    'summary',
    'source',
    'highlight_title',
    'image_description',
    'news_file_id',
    'type_id',
    'photo_album_id',
    'playlist_id',
    'use_watermark',
    'is_draft',
    'is_disabled',
  ];

  public function sluggable()
  {
    return [
      'slug' => [
        'source' => 'title',
      ],
    ];
  }

  protected static function boot()
  {
    parent::boot(); // TODO: Change the autogenerated stub

    self::creating( function ( $news ) {
      $news->attributes['title']       = $news->attributes['title'] == null ? 'draft' : $news->attributes['title'];
      $news->attributes['category_id'] = $news->attributes['category_id'] == null ? 2 : $news->attributes['category_id'];
      $news->attributes['content']     = $news->attributes['content'] == null ? 'draft' : $news->attributes['content'];
      $news->attributes['image']       = $news->attributes['image'] == null ? 'draft' : $news->attributes['image'];
      $news->created_by                = Auth::user()->id;
      $news->modified_by               = Auth::user()->id;
      $news->slug                      = Str::limit( $news->slug ?? 'مسودة', 37 );

    } );

    self::created( function ( $news ) {
      if ( is_null( $news->date_to_publish ) || $news->date_to_publish === null )
      {
        $news->date_to_publish = $news->created_at;
      }
    } );

    self::created( function ( $news ) {
//      Meta::data( $news, request()->all() );

    } );

    self::updating( function ( $news ) {
      $news->attributes['title']       = $news->attributes['title'] == null ? 'draft' : $news->attributes['title'];
      $news->attributes['category_id'] = $news->attributes['category_id'] == null ? 1 : $news->attributes['category_id'];
      $news->attributes['content']     = $news->attributes['content'] == null ? 'draft' : $news->attributes['content'];
      $news->attributes['image']       = $news->attributes['image'] == null ? 'draft' : $news->attributes['image'];
    } );

    self::updated( function ( $news ) {
      $news->modified_by = Auth::user()->id;
    } );
  }

  public function getMorphClass()
  {
    return 'News';
  }

  public function tags()
  {
    return $this->morphToMany( Tag::class, 'taggable' )
                ->where( 'is_disabled', 0 )
                ->select( [ 'id', 'name', 'slug' ] );
  }

  public function metas()
  {
    return $this->morphOne( Meta::class, 'metaable' );

  }

  public function category()
  {
    return $this->belongsTo( Category::class );
  }

  public function publisher()
  {
    return $this->belongsTo( User::class, 'created_by' )
                ->select( [ 'id', 'display_name', 'description', 'facebook', 'twitter', 'image', 'mobile' ] );
  }

  public function photos()
  {
    return $this->hasMany( Photo::class, 'news_id' );

  }

  public function setVideoAttribute( $value )
  {
    if ( is_null( $value ) )
    {
      return $this->attributes['video'] = '';
    }

    preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                $value,
                $match );

    $this->attributes['video'] = 'https://www.youtube.com/embed/' . $match[1];
  }

  public function setDateToPublishAttribute( $value )
  {
    $date                                = $this->parseDate( $value );
    $this->attributes['date_to_publish'] = $date;

  }

  public function scopeLive( $query )
  {
    return $query->where( 'is_draft', 0 )
                 ->where( 'is_disabled', 0 )
                 ->where( 'is_archived', 0 )
                 ->where( 'date_to_publish', '<=', now() );
  }

  public function scopeOrder( $query )
  {
    return $query->orderBy( 'id', 'DESC' );
  }

  public function scopeMain( $query, $currentNewsId = null )
  {
    return $query->live()
                 ->where( 'is_main_news', 1 )
                 ->where( 'news.id', '!=', $currentNewsId );
  }

  public function scopeSpecial( $query )
  {
    return $query->live()
                 ->where( 'is_special_news', 1 );
  }

  /**
   * @param $mainCount
   * @param $specialCount
   *
   * @return News|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
   */
  public static function getRemaining( $mainCount = 4, $specialCount = 4 )
  {


    $main     = News::main()->take( $mainCount )->get();
    $special  = News::special()->take( $specialCount )->get();
    $excluded = [];

    foreach ( $special as $data )
    {
      array_push( $excluded, $data->id );
    }
    foreach ( $main as $data )
    {
      array_push( $excluded, $data->id );
    }

    return News::live()
               ->whereNotIn( 'news.id', $excluded )
               ->orderBy( 'news.id', 'desc' );

  }

  /**
   * @param $query
   *
   * @return News|\Illuminate\Database\Eloquent\Builder
   */
  public function scopeEvents( $query )
  {
    return $query->live()
                 ->where( 'is_news_ticker', '=', 1 );
  }

  public function scopeMostRead( $query, $id )
  {
    return $query->live()
                 ->where( 'date_to_publish', '>', today()->subWeek() )
                 ->where( 'id', '!=', $id )
                 ->orderBy( 'no_of_views', 'DESC' )
                 ->order();
  }

  public function scopeSeparate( $query )
  {
    return $query->where( 'category_id', '86261' );
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

  public static function getByCategory( $category_id = null )
  {
    return News::live()->where( 'category_id', $category_id )->order();
  }

  public static function getByCategorySlug( $slug )
  {
    $category_id = Category::where( 'slug', $slug )->select( 'id' )->pluck( 'id' );

    return News::getByCategory( $category_id );
  }

  public static function getByTag( $id )
  {
    $tag = Tag::find( $id );
    if ( $tag )
    {
      return $tag->news();
    } else
    {
      return News::where( 'id', 0 );
    }
    //Alternate for used as a scope
//        return $query->with('tags')->whereHas('tags', function ($query) use ($id) {
//            $query->where('tags.id', $id);
//        });
  }

  public static function getByTagSlug( $slug )
  {
    $id = Tag::where( 'slug', $slug )->select( 'id' )->pluck( 'id' );

    return News::getByTag( $id->first() );
  }

  public function parseDate( $value )
  {
    $timestamp = strtotime( $value );

    return date( 'Y-m-d H:i', $timestamp );

  }

  public function getUrlAttribute()
  {
    return route( 'news', [ 'slug' => $this->id . '-' . $this->slug ] );
  }

  public function increaseView()
  {
    $this->timestamps = false;
    get_class( $this )::where( 'id', $this->id )->update( [ 'no_of_views' => $this->no_of_views + 1 ] );
    $this->timestamps = true;

    return $this;
  }

  public static function shareFB( $news )
  {
    return SendTo::Facebook(
      'link',
      [
        'link'    => ( new static )->domain . '/' . $news->id,
        'message' => $news->title,
      ]
    );
  }

  public static function shareTwitter( $news )
  {
    return SendTo::Twitter(
      $news->title,
      [
        $news->url,
      ]
    );

  }

  public function pushNotification( $news )
  {
    $content      = [
      "en" => 'English Message',
    ];
    $hashes_array = [];
    array_push( $hashes_array, [
      "id"   => $news->id,
      "text" => $news->title,
      "icon" => $news->image,
      "url"  => $news->url,
    ] );
    $fields = [
      'app_id'            => "207d6485-8995-4971-a5a6-1be0aeb2b137",
      'included_segments' => [
        'All',
      ],
      'contents'          => $content,
      'web_buttons'       => $hashes_array,
    ];

    $fields = json_encode( $fields );

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications" );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json; charset=utf-8',
      'Authorization: Basic YzM1OTAxYmEtYTM2NS00NjgyLTkzYmQtMWYxZjdhY2E0OTBi',
    ] );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HEADER, false );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

    curl_exec( $ch );
    curl_close( $ch );

  }

  public function type()
  {
    return $this->belongsTo( Type::class );
  }

}
