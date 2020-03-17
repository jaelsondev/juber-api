<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserPost extends Model
{

  protected $table = "user_post";

  protected $fillable = [
    'like', 'user_id', 'post_id'
  ];

  public function user()
  {
    return $this->hasOne('App\User');
  }

  public function post()
  {
    return $this->hasOne('App\Models\Post');
  }
  
}
