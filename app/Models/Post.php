<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{

  protected $table = "posts";

  protected $fillable = [
    'title', 'subtitle', 'content', 'user_id'
  ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }
  
  public function likes() {
    return $this->hasMany('App\Models\UserPost');
  }
}
