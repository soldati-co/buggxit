<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DressResource;
use App\Http\Resources\HeroSlideResource;
use App\Models\Category;
use App\Models\Dress;
use App\Models\HeroSlide;

class HomeApiController extends Controller
{
    public function index()
    {
        $featuredDresses = Dress::with('categories')
            ->where('is_featured', true)
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        $newArrivals = Dress::with('categories')
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        $activeCategories = Category::withCount('dresses')
            ->where('is_active', true)
            ->having('dresses_count', '>', 0)
            ->orderBy('sort_order')
            ->get();

        $heroSlides = HeroSlide::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'hero_slides' => HeroSlideResource::collection($heroSlides),
            'featured' => DressResource::collection($featuredDresses),
            'new_arrivals' => DressResource::collection($newArrivals),
            'categories' => CategoryResource::collection($activeCategories),
        ]);
    }
}
