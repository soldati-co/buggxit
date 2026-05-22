<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Featured dresses (max 4)
        $featuredDresses = Dress::where('is_featured', true)
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        // 2. New arrivals (latest 4 active dresses)
        $newArrivals = Dress::where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        // 3. Full category definitions (icons, names, descriptions)
        $categories = [
            'SLMK' => ['name' => 'Slay Makoti dress set', 'icon' => 'fas fa-crown', 'description' => 'For the Makoti who walks in and owns the room.'],
            'ZMBN' => ['name' => 'Zimbini dress set', 'icon' => 'fas fa-fan', 'description' => 'Two looks. One statement.'],
            'CLPS' => ['name' => 'Classic Panel skirt set', 'icon' => 'fas fa-vest', 'description' => 'Structure. Elegance. Always in style.'],
            'NKWA' => ['name' => 'Nokwanda dress set', 'icon' => 'fas fa-gem', 'description' => 'Graceful, grounded, made to be remembered.'],
            'PNDK' => ['name' => 'Phenduka Reversable skirts dress set', 'icon' => 'fas fa-ribbon', 'description' => 'Turn heads with two looks. Own the moment.'],
            'SLBL' => ['name' => 'Slay Bubble dress set', 'icon' => 'fas fa-bubble', 'description' => 'Playful and feminine for a charming look.'],
            'CUSTOM' => ['name' => 'Bespoke dress set', 'icon' => 'fas fa-pen-fancy', 'description' => 'Tailored to your unique style and preferences.'],
        ];

        // 4. Build active categories with live counts
        $activeCategories = [];
        foreach ($categories as $sku => $cat) {
            $count = Dress::where('sku_prefix', $sku)->count();
            $activeCategories[$sku] = array_merge($cat, ['count' => $count]);
        }

        // 5. Keep only categories that have at least one dress
        $activeCategories = array_filter($activeCategories, fn($cat) => $cat['count'] > 0);

        // 6. Return the view with both arrays
        return view('pages.landing', compact(
            'featuredDresses',
            'newArrivals',
            'categories',        // full metadata (used in featured dress loop)
            'activeCategories'   // filtered, with counts (used in collections grid)
        ));
    }
}