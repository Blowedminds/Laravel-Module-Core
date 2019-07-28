<?php

namespace App\Modules\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
  use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'integer', 'menu_weight' => 'integer', 'menu_parent' => 'integer', 'name' => 'array', 'tooltip' => 'array'
    ];

    protected $guarded = [];

    public function menuRoles()
    {
      return $this->hasMany('App\Modules\Core\MenuRole');
    }

    public function roles()
    {
        return $this->belongsToMany('App\\Modules\\Core\\Role', 'menu_roles');
    }
}
