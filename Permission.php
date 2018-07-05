<?php

namespace App\Modules\Core;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    public function roles()
    {
        return $this->hasManyThrough('App\Modules\Core\Role', 'role_permissions');
    }
}
