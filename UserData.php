<?php

namespace App\Modules\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserData extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $table = 'user_datas';

    protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'integer',
        'role_id' => 'integer',
        'biography' => 'array'
    ];

    protected $fillable = [
        'user_id', 'name', 'role_id', 'profile_image', 'biography'
    ];

    public function user()
    {
        return $this->belongsTo('App\\Modules\\Core\\User', 'user_id', 'user_id');
    }
}
