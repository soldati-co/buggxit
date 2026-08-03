<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function health()
    {
        return response('ok', 200);
    }
}
