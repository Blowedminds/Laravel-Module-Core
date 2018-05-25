<?php

namespace App\Modules\Core;

use Illuminate\Database\Eloquent\Model;

class ArticlePermission extends Model
{
  protected $casts = [ 'id' => 'integer', 'article_id' => 'integer' ];

  protected $fillable = [
    'article_id', 'user_id'
  ];

  protected $hidden = [

  ];

}
