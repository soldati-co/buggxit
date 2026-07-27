<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dress;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all active dresses.
     */
    public function index(Request $request)
    {
        return view('products.index');
    }

    public function newArrivals()
    {
        return view('products.index', [
            'newArrivals' => true,
        ]);
    }

    /**
     * Display the specified dress.
     */
    public function show(string $dressIdentifier)
    {
        return view('products.show', [
            'productIdentifier' => $dressIdentifier,
        ]);
    }

}