<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dress;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminDressController extends Controller
{
    private const ALLOWED_IMAGE_MIMETYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        'image/bmp',
        'image/avif',
    ];

    private $skuPrefixes = [
        'SLMK' => 'Slay Makoti',
        'ZMBN' => 'Zimbini',
        'CLPS' => 'Classic Panel',
        'NKWA' => 'Nokwanda',
        'PNDK' => 'Phenduka',
        'SLBL' => 'Slay Bubble',
        'CUSTOM' => 'Other (Custom SKU)',
    ];

    private $availableSizes = [32, 34, 36, 38, 40, 42];

    private function checkAuth()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $dresses = Dress::with('categories')->latest()->paginate(10);
        return view('admin.dresses.index', ['dresses' => $dresses]);
    }

    public function create()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.dresses.create', [
            'categories'      => $categories,
            'skuPrefixes'     => $this->skuPrefixes,
            'availableSizes'  => $this->availableSizes,
            'availableColors' => Dress::STANDARD_COLORS,
        ]);
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        try {
            $validated = $this->validateDress($request);

            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $validated['stock_quantity'] = $validated['stock_quantity'] ?? 0;
            $validated['is_taxable'] = $validated['is_taxable'] ?? true;
            $validated['requires_shipping'] = $validated['requires_shipping'] ?? true;
            $validated['is_featured'] = $validated['is_featured'] ?? false;

            if ($validated['sku_prefix'] !== 'CUSTOM') {
                $validated['custom_sku'] = null;
            }

            // Remove image fields from validated data (we handle them separately)
            unset($validated['main_image'], $validated['gallery_images']);

            $dress = Dress::create($validated);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $this->storeMainImage($request->file('main_image'), $dress);
            }

            // Handle gallery images upload
            if ($request->hasFile('gallery_images')) {
                $this->storeGalleryImages($request->file('gallery_images'), $dress);
            }

            if ($request->has('category_ids')) {
                $dress->categories()->sync($request->input('category_ids', []));
            }

            return redirect()->route('admin.dresses.index')
                ->with('success', 'Dress created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Dress store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $dress->load('categories');
        return view('admin.dresses.show', ['dress' => $dress]);
    }

    public function edit(Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $categories = Category::orderBy('sort_order')->get();
        $selectedCategories = $dress->categories->pluck('id')->toArray();
        return view('admin.dresses.edit', [
            'dress'              => $dress,
            'categories'         => $categories,
            'selectedCategories' => $selectedCategories,
            'skuPrefixes'        => $this->skuPrefixes,
            'availableSizes'     => $this->availableSizes,
            'availableColors'    => Dress::STANDARD_COLORS,
        ]);
    }

    public function update(Request $request, Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        try {
            $validated = $this->validateDress($request, $dress);

            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            if ($validated['sku_prefix'] !== 'CUSTOM') {
                $validated['custom_sku'] = null;
            }

            // Remove image fields from validated data
            unset($validated['main_image'], $validated['gallery_images']);

            // Handle main image replacement
            if ($request->hasFile('main_image')) {
                // Delete old main image record
                $dress->mainImage()->delete();
                $this->storeMainImage($request->file('main_image'), $dress);
            }

            // Handle gallery images replacement (you might want to add/remove individually instead)
            if ($request->hasFile('gallery_images')) {
                // Delete all old gallery images
                $dress->galleryImages()->delete();
                $this->storeGalleryImages($request->file('gallery_images'), $dress);
            }

            $dress->update($validated);
            $dress->categories()->sync($request->input('category_ids', []));

            return redirect()->route('admin.dresses.index')
                ->with('success', 'Dress updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Dress update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        // Delete all associated images (polymorphic)
        $dress->images()->delete();
        $dress->delete();
        return redirect()->route('admin.dresses.index')
            ->with('success', 'Dress deleted successfully!');
    }

    public function toggleFeatured(Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $dress->update(['is_featured' => !$dress->is_featured]);
        $status = $dress->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Dress {$status} successfully!");
    }

    public function updateStatus(Request $request, Dress $dress)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $request->validate(['status' => 'required|in:draft,active,out_of_stock']);
        $dress->update(['status' => $request->status]);
        return back()->with('success', 'Status updated successfully!');
    }

    // ---------- Private helper methods ----------

    private function validateDress(Request $request, ?Dress $dress = null)
    {
        $standardColorKeys = array_keys(Dress::STANDARD_COLORS);
        $skuPrefixKeys = array_keys($this->skuPrefixes);

        $rules = [
            'slug' => 'nullable|string|max:255|unique:dresses,slug' . ($dress ? ',' . $dress->id : ''),
            'name' => 'required|string|max:255',
            'sku_prefix' => 'required|in:' . implode(',', $skuPrefixKeys),
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
            'colors.*' => [
                'string',
                function ($attribute, $value, $fail) use ($standardColorKeys) {
                    if (in_array($value, $standardColorKeys)) return;
                    if (preg_match('/^#?([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value)) return;
                    $fail('Each color must be a standard color name or a valid hex code.');
                }
            ],
            'status' => 'required|in:draft,active,out_of_stock',
            'is_featured' => 'nullable|boolean',
            'is_taxable' => 'nullable|boolean',
            'requires_shipping' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'main_image' => 'nullable|file|max:9048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'file|max:9048',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
        ];

        return $request->validate($rules);
    }

    /**
     * Store a main image as database binary.
     */
    private function storeMainImage($file, Dress $dress)
    {
        $mime = $file->getMimeType();
        if (!in_array($mime, self::ALLOWED_IMAGE_MIMETYPES)) {
            throw new \Exception('Unsupported image type: ' . $mime);
        }

        $dress->images()->create([
            'image_data' => file_get_contents($file->getRealPath()),
            'image_mime' => $mime,
            'collection' => 'main',
            'sort_order' => 0,
        ]);
    }

    /**
     * Store multiple gallery images as database binaries.
     */
    private function storeGalleryImages($files, Dress $dress)
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $index => $file) {
            $mime = $file->getMimeType();
            if (!in_array($mime, self::ALLOWED_IMAGE_MIMETYPES)) {
                continue; // skip invalid files
            }
            $dress->images()->create([
                'image_data' => file_get_contents($file->getRealPath()),
                'image_mime' => $mime,
                'collection' => 'gallery',
                'sort_order' => $index + 1,
            ]);
        }
    }
}