<?php

namespace Mezian\Zaina\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

  protected $dates = [ 'deleted_at' ];

  protected $with = [ 'roles' ];

  use Notifiable, HasRoles, HasApiTokens;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'username',
    'email',
    'password',
    'first_name',
    'last_name',
    'display_name',
    'mobile',
    'facebook',
    'twitter',
    'image',
    'description',
    'address',
    'is_disable',
    'type_id',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  public function news()
  {
    return $this->hasMany( News::class, 'created_by' );

  }

  public function articles()
  {
    return $this->hasMany( Article::class, 'created_by' );

  }

}
