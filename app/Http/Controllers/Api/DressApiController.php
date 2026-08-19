<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DressResource;
use App\Models\Category;
use App\Models\Dress;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DressApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Dress::with('categories')->withImageUrls()->where('status', 'active');

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
}
