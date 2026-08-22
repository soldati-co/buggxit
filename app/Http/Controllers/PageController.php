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

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function termsOfService()
    {
        return view('pages.terms-of-service');
    }

    public function shippingPolicy()
    {
        return view('pages.shipping-policy');
    }

    public function returnsRefund()
    {
        return view('pages.returns-refund');
    }

    public function sizeGuide()
    {
        return view('pages.size-guide');
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
