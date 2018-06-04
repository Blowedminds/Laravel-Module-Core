<?php

namespace App\Modules\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'article_id'
    ];

    protected $hidden = [

    ];

    public function article()
    {
        return $this->hasOne('App\Modules\Core\Article', 'id', 'article_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Modules\Core\RoomMessage', 'room_id');
    }

    public function scopeWithMessagesAndUser($query)
    {
        return $query->with(['messages' => function($q) {
            $q->withUser();
        }]);
    }
}
