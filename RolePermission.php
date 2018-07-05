<?php

namespace App\Modules\Core;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded = [];

    public function permission()
    {
        return $this->hasOne('App\Modules\Core\Permission');
    }

    public function role()
    {
        return $this->hasOne('App\Modules\Core\Role');
    }
}
