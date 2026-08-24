<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DressResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Dress;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Dress::with('categories')->withImageUrls()->where('status', 'active');

        if ($request->filled('search')) {
            $term = strtolower($request->input('search'));

            $query->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$term}%"]);
            });
        }

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)
                ->when(
                    Str::isUuid($request->category),
                    fn ($query) => $query->orWhere('id', $request->category)
                )
                ->first();

            if ($category) {
                $query->whereHas('categories', fn ($query) => $query->where('categories.id', $category->id));
            }
        }

        match ($request->input('sort')) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->latest(),
        };

        $dresses = $query->paginate(12);
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
            ->withImageUrls()
            ->where('status', 'active')
            ->latest()
            ->take(12)
            ->get();

        return DressResource::collection($dresses);
    }
}
