<?php

namespace App\Modules\Core\Http\Middleware;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class SanitizeStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation'
    ];

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }
        //For the article module, purify the hmtl
        else if($key === 'body') {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Cache.SerializerPath', storage_path('framework/cache'));
            return (new HTMLPurifier($config))->purify($value);
        }

        return is_string($value) ? htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8') : $value;
    }
}
