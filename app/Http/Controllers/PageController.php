<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    // Metode ini akan dipanggil oleh route
    public function aboutUs()
    {
        // 'user.aboutUs' mengarah ke folder views/user/aboutUs.blade.php
        return view('aboutUs');
    }
}
