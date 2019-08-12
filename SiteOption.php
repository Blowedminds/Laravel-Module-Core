<?php

namespace App\Modules\Core;

use Illuminate\Database\Eloquent\Model;

class SiteOption extends Model
{
    protected $fillable = [
        'key', 'type', 'value'
    ];
}
