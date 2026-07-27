<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DressResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Dress;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
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
        return DressResource::collection($dresses);
    }

    public function show(Dress $dress)
    {
        if ($dress->status !== 'active') {
            return response()->json(['message' => 'Dress not found.'], 404);
        }

        return new DressResource($dress->load('categories'));
    }

    public function newArrivals()
    {
        $dresses = Dress::with('categories')
            ->where('status', 'active')
            ->latest()
            ->take(12)
            ->get();

        return DressResource::collection($dresses);
    }
}
