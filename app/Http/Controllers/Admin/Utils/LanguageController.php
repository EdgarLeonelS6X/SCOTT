<?php

namespace App\Http\Controllers\Admin\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switchLanguage(Request $request)
    {
        $locale = $request->input('locale', config('app.locale'));

        if (in_array($locale, ['en', 'es'])) {
            session(['locale' => $locale]);

            app()->setLocale($locale);
        }

        return back();
    }
}
