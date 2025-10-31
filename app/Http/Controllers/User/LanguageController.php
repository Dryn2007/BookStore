<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLanguage(Request $request)
    {
        $locale = $request->input('locale', 'id');

        // Validate locale
        if (!in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        // Store in session
        Session::put('locale', $locale);

        // Set app locale
        app()->setLocale($locale);

        return redirect()->back();
    }
}
