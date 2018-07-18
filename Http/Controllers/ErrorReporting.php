<?php

namespace App\Modules\Core\Http\Controllers;


use Illuminate\Support\Facades\Log;

class ErrorReporting
{
    public function postError()
    {
        Log::error(request()->all());

        return response()->json(request()->all());
    }
}