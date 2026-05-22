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

        // Filter by category (sku_prefix)
        if ($request->has('category') && array_key_exists($request->category, $this->categories())) {
            $query->where('sku_prefix', $request->category);
        }

        $dresses = $query->latest()->paginate(12);

        return view('products.index', [
            'dresses' => $dresses,
            'categories' => $this->categories(),
            'currentCategory' => $request->category,
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
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Helper: category definitions with icons and names.
     */
    private function categories()
    {
        return [
            'SLMK' => ['name' => 'Slay Makoti dress set', 'icon' => 'fas fa-crown', description => 'For the Makoti who walks in and owns the room.' ],
            'ZMBN' => ['name' => 'Zimbini dress set', 'icon' => 'fas fa-fan', description => 'Two looks. One statement.' ],
            'CLPS' => ['name' => 'Classic Panel skirt set', 'icon' => 'fas fa-vest', description => 'Structure. Elegance. Always in style.' ],
            'NKWA' => ['name' => 'Nokwanda dress set', 'icon' => 'fas fa-gem', description => 'Graceful, grounded, made to be remembered.' ],
            'PNDK' => ['name' => 'Phenduka Reversable skirts dress set', 'icon' => 'fas fa-ribbon', description => 'Turn heads with two looks. Own the moment.' ],
            'SLBL' => ['name' => 'Slay Bubble dress set', 'icon' => 'fas fa-bubble', description => 'Playful and feminine for a charming look.' ],
            'CUSTOM' => ['name' => 'Bespoke dress set', 'icon' => 'fas fa-pen-fancy', description => 'Tailored to your unique style and preferences.' ],
        ];
    }
}