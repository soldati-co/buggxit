<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all active dresses.
     */
    public function index(Request $request)
    {
        $query = Dress::where('status', 'active');
        $dresses = $query->latest()->paginate(12);

        return view('products.index', [
            'dresses' => $dresses,
        ]);
    }

    /**
     * Display the specified dress.
     */
    public function show(Dress $dress)
    {
        // Ensure only active dresses are viewable
        if ($dress->status !== 'active') {
            abort(404);
        }

        return view('products.show', [
            'dress' => $dress,
        ]);
    }

}