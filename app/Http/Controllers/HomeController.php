<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        if (! Schema::hasTable('dresses')) {
            $featuredDresses = collect();
            $newArrivals = collect();
            $activeCategories = collect();

            return view('pages.landing', compact(
                'featuredDresses',
                'newArrivals',
                'activeCategories'
            ));
        }
        // 1. Featured dresses (max 4), with their categories loaded
        $featuredDresses = Dress::with('categories')
            ->where('is_featured', true)
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        // 2. New arrivals (latest 4 active dresses)
        $newArrivals = Dress::where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        // 3. Active categories with associated dresses, ordered by sort_order
        $activeCategories = Category::withCount('dresses')
            ->where('is_active', true)
            ->whereHas('dresses')
            ->orderBy('sort_order')
            ->get();

        return view('pages.landing', compact(
            'featuredDresses',
            'newArrivals',
            'activeCategories'
        ));
    }
}