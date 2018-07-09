<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    public $fillable = [
        'channel'
    ];

    public function messages()
    {
        return $this->hasMany('App\Modules\Core\RoomMessage');
    }
}