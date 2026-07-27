<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
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

        // 3. Active categories with dress count, ordered by sort_order
        $activeCategories = Category::withCount('dresses')
            ->where('is_active', true)
            ->having('dresses_count', '>', 0)
            ->orderBy('sort_order')
            ->get();

        return view('pages.landing');
    }
}