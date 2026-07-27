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
        $query = Dress::with('categories')->where('status', 'active');

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)
                ->orWhere('id', $request->category)
                ->first();

            if ($category) {
                $query->whereHas('categories', fn ($query) => $query->where('categories.id', $category->id));
            }
        }

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