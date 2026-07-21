<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminDressController extends Controller
{
    // Dress categories with SKU prefixes (keep as is)
    private $dressCategories = [
        'SLMK' => 'Slay Makoti Dress Set',
        'ZMBN' => 'Zimbini Dress Set',
        'CLPS' => 'Classic Panel Skirt Apron and Doek Set',
        'NKWA' => 'Nokwanda Dress set',
        'PNDK' => 'Phenduka Skirt',
        'SLBL' => 'Slay Bubble Dress',
        'CUSTOM' => 'Other (Custom SKU)',
    ];

    // Available sizes (32-42) - keep as is
    private $availableSizes = [32, 34, 36, 38, 40, 42];

    /**
     * Manual authentication check – redirects to login if not authenticated.
     */
    private function checkAuth()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        return null;
    }

    /**
     * Display a listing of dresses.
     */
    public function index()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $dresses = Dress::latest()->paginate(10);
        
        return view('admin.dresses.index', [
            'dresses' => $dresses,
            'categories' => $this->dressCategories,
        ]);
    }

    /**
     * Show the form for creating a new dress.
     */
    public function create()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        return view('admin.dresses.create', [
            'categories' => $this->dressCategories,
            'availableSizes' => $this->availableSizes,
            'availableColors' => Dress::STANDARD_COLORS, // pass the constant
        ]);
    }

    /**
     * Store a newly created dress.
     */
    public function store(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $validated = $this->validateDress($request);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Set defaults for missing fields
        $validated['stock_quantity'] = $validated['stock_quantity'] ?? 0;
        $validated['is_taxable'] = $validated['is_taxable'] ?? true;
        $validated['requires_shipping'] = $validated['requires_shipping'] ?? true;
        $validated['is_featured'] = $validated['is_featured'] ?? false;

        // If not CUSTOM, clear custom_sku
        if ($validated['sku_prefix'] !== 'CUSTOM') {
            $validated['custom_sku'] = null;
        }

        // Handle image uploads
        $this->handleImages($request, $validated);

        Dress::create($validated);

        return redirect()->route('admin.dresses.index')
            ->with('success', 'Dress created successfully!');
    }

    /**
     * Display the specified dress.
     */
    public function show(Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        return view('admin.dresses.show', [
            'dress' => $dress,
            'categories' => $this->dressCategories,
        ]);
    }

    /**
     * Show the form for editing the specified dress.
     */
    public function edit(Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        return view('admin.dresses.edit', [
            'dress' => $dress,
            'categories' => $this->dressCategories,
            'availableSizes' => $this->availableSizes,
            'availableColors' => Dress::STANDARD_COLORS,
        ]);
    }

    /**
     * Update the specified dress.
     */
    public function update(Request $request, Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $validated = $this->validateDress($request, $dress);

        // Auto-generate slug if not provided (or if it's empty)
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // If not CUSTOM, clear custom_sku
        if ($validated['sku_prefix'] !== 'CUSTOM') {
            $validated['custom_sku'] = null;
        }

        // Handle image uploads (with deletion of old files)
        $this->handleImages($request, $validated, $dress);

        $dress->update($validated);

        return redirect()->route('admin.dresses.index')
            ->with('success', 'Dress updated successfully!');
    }

    /**
     * Remove the specified dress.
     */
    public function destroy(Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        // Delete images from storage
        $this->deleteDressImages($dress);
        $dress->delete();

        return redirect()->route('admin.dresses.index')
            ->with('success', 'Dress deleted successfully!');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $dress->update(['is_featured' => !$dress->is_featured]);
        $status = $dress->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Dress {$status} successfully!");
    }

    /**
     * Update status.
     */
    public function updateStatus(Request $request, Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $request->validate([
            'status' => 'required|in:draft,active,out_of_stock'
        ]);
        $dress->update(['status' => $request->status]);
        return back()->with('success', 'Status updated successfully!');
    }

    // ---------- Private helper methods ----------

    /**
     * Validate dress data (store and update).
     */
    private function validateDress(Request $request, ?Dress $dress = null)
    {
        $standardColorKeys = array_keys(Dress::STANDARD_COLORS);

        $rules = [
            'slug' => 'nullable|string|max:255|unique:dresses,slug' . ($dress ? ',' . $dress->id : ''),
            'name' => 'required|string|max:255',
            'sku_prefix' => 'required|in:' . implode(',', array_keys($this->dressCategories)),
            'custom_sku' => 'nullable|required_if:sku_prefix,CUSTOM|string|max:20',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'turnaround_time' => 'required|string|max:100',
            'expected_delivery' => 'required|string|max:100',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'in:' . implode(',', $this->availableSizes),
            'colors' => 'required|array|min:1',
            'colors.*' => [ // custom validation for standard keys or hex
                'string',
                function ($attribute, $value, $fail) use ($standardColorKeys) {
                    if (in_array($value, $standardColorKeys)) {
                        return;
                    }
                    // Check if it's a valid hex code (with or without #)
                    if (preg_match('/^#?([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value)) {
                        return;
                    }
                    $fail('Each color must be a standard color name or a valid hex code.');
                }
            ],
            'status' => 'required|in:draft,active,out_of_stock',
            'is_featured' => 'nullable|boolean',
            'is_taxable' => 'nullable|boolean',
            'requires_shipping' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'main_image' => 'nullable|image|max:9048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:9048',
        ];

        return $request->validate($rules);
    }

    /**
     * Handle main and gallery image uploads.
     * If $dress is provided, delete old images before uploading new ones.
     */
    private function handleImages(Request $request, array &$validated, ?Dress $dress = null)
    {
        // Handle main image
        if ($request->hasFile('main_image')) {
            if ($dress && $dress->main_image_url) {
                $this->deleteImageFile($dress->main_image_url);
            }
            $path = $request->file('main_image')->store('dresses', 'public');
            $validated['main_image_url'] = Storage::url($path);
        } else {
            // If no new image is uploaded, keep the existing one (we don't unset)
            // For store, we keep it null if not provided.
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            if ($dress && $dress->gallery_images) {
                foreach ($dress->gallery_images as $oldImage) {
                    $this->deleteImageFile($oldImage);
                }
            }
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('dresses/gallery', 'public');
                $galleryPaths[] = Storage::url($path);
            }
            $validated['gallery_images'] = $galleryPaths;
        } else {
            // If no new gallery, we keep the old ones (for update)
            // For store, it stays null.
        }
    }

    /**
     * Delete a single image file from storage.
     */
    private function deleteImageFile(string $url)
    {
        // Convert URL to storage path (e.g., /storage/dresses/abc.jpg -> public/dresses/abc.jpg)
        $path = str_replace('/storage/', 'public/', $url);
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    /**
     * Delete all images associated with a dress.
     */
    private function deleteDressImages(Dress $dress)
    {
        if ($dress->main_image_url) {
            $this->deleteImageFile($dress->main_image_url);
        }
        if ($dress->gallery_images) {
            foreach ($dress->gallery_images as $image) {
                $this->deleteImageFile($image);
            }
        }
    }
}