<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Language;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        $this->middleware('permission:ownership.language')->except([
            'getLanguages'
        ]);
    }

    public function getLanguages()
    {
        return Language::all();
    }

    public function putLanguage($language_id)
    {
        request()->validate([
            'name' => 'required',
            'slug' => 'required'
        ]);

        Language::findOrFail($language_id)->update(request()->only(['name', 'slug']));

        return response()->json();
    }

    public function postLanguage()
    {
        request()->validate([
            'name' => 'required',
            'slug' => 'required'
        ]);

        Language::create(request()->only(['name', 'slug']));

        return response()->json();
    }

    public function deleteLanguage($language_id)
    {
        Language::findOrFail($language_id)->forceDelete();

        return response()->json();
    }
}
