<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dress;
use App\Models\Category;
use App\Services\ImageStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class AdminDressController extends Controller
{
    private $availableSizes = [32, 34, 36, 38, 40, 42];

    public function __construct(private ImageStorageService $images)
    {
    }

    public function index()
    {
        $dresses = Dress::with('categories')->withImageUrls()->latest()->paginate(10);
        return view('admin.dresses.index', ['dresses' => $dresses]);
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order', 'asc')->get();
        return view('admin.dresses.create', [
            'categories'      => $categories,
            'availableSizes'  => $this->availableSizes,
            'availableColors' => Dress::STANDARD_COLORS,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validateDress($request);

            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $validated['stock_quantity'] = $validated['stock_quantity'] ?? 0;
            $validated['is_taxable'] = $validated['is_taxable'] ?? true;
            $validated['requires_shipping'] = $validated['requires_shipping'] ?? true;
            $validated['is_featured'] = $validated['is_featured'] ?? false;

            // Remove image fields from validated data (we handle them separately)
            unset($validated['main_image'], $validated['gallery_images'], $validated['card_image'], $validated['remove_card_image']);

            $dress = Dress::create($validated);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $this->storeMainImage($request->file('main_image'), $dress);
            }

            // Handle card-crop image upload
            if ($request->hasFile('card_image')) {
                $this->storeCardImage($request->file('card_image'), $dress);
            }

            // Handle gallery images upload
            if ($request->hasFile('gallery_images')) {
                $this->storeGalleryImages($request->file('gallery_images'), $dress);
            }

            if ($request->has('category_ids')) {
                $dress->categories()->sync($request->input('category_ids', []));
            }

            return redirect()->route('admin.dresses.index', [])
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
        $dress->load('categories');
        return view('admin.dresses.show', ['dress' => $dress]);
    }

    public function edit(Dress $dress)
    {
        $categories = Category::orderBy('sort_order', 'asc')->get();
        $selectedCategories = $dress->categories->pluck('id')->toArray();
        return view('admin.dresses.edit', [
            'dress'              => $dress,
            'categories'         => $categories,
            'selectedCategories' => $selectedCategories,
            'availableSizes'     => $this->availableSizes,
            'availableColors'    => Dress::STANDARD_COLORS,
        ]);
    }

    public function update(Request $request, Dress $dress)
    {
        try {
            $validated = $this->validateDress($request, $dress);

            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Remove image fields from validated data
            unset($validated['main_image'], $validated['gallery_images'], $validated['card_image'], $validated['remove_card_image']);

            // Handle main image replacement
            if ($request->hasFile('main_image')) {
                // Delete old main image record (delete model if exists)
                $mainImage = $dress->mainImage()->first();
                if ($mainImage) {
                    $mainImage->delete();
                }
                $this->storeMainImage($request->file('main_image'), $dress);
            }

            // A card crop is deliberately (a) removed via the "Remove Crop"
            // button, or (b) replaced by a fresh crop — both delete the old
            // 'card' image row first, same delete-before-replace convention
            // as main_image/gallery above.
            if ($request->boolean('remove_card_image') || $request->hasFile('card_image')) {
                $cardImage = $dress->cardImage()->first();
                if ($cardImage) {
                    $cardImage->delete();
                }
            }
            if ($request->hasFile('card_image')) {
                $this->storeCardImage($request->file('card_image'), $dress);
            }

            // Handle gallery images replacement
            if ($request->hasFile('gallery_images')) {
                // Delete all old gallery images (delete each model)
                foreach ($dress->galleryImages()->get() as $oldImg) {
                    $oldImg->delete();
                }
                $this->storeGalleryImages($request->file('gallery_images'), $dress);
            }

            $dress->update($validated);
            $dress->categories()->sync($request->input('category_ids', []));

            return redirect()->route('admin.dresses.index', [])
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
        // Delete all associated images (delete each model)
        foreach ($dress->images()->get() as $img) {
            $img->delete();
        }
        Dress::destroy($dress->id);
        return redirect()->route('admin.dresses.index', [])
            ->with('success', 'Dress deleted successfully!');
    }

    public function toggleFeatured(Dress $dress)
    {
        $dress->update(['is_featured' => !$dress->is_featured]);
        $status = $dress->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Dress {$status} successfully!");
    }

    public function updateStatus(Request $request, Dress $dress)
    {
        $request->validate(['status' => 'required|in:draft,active,out_of_stock']);
        $dress->update(['status' => $request->status]);
        return back()->with('success', 'Status updated successfully!');
    }

    // ---------- Private helper methods ----------

    private function validateDress(Request $request, ?Dress $dress = null)
    {
        $standardColorKeys = array_keys(Dress::STANDARD_COLORS);

        $rules = [
            'slug' => 'nullable|string|max:255|unique:dresses,slug' . ($dress ? ',' . $dress->id : ''),
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:dresses,sku' . ($dress ? ',' . $dress->id : ''),
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
            'card_image' => 'nullable|file|mimes:jpeg,jpg|max:9048',
            'remove_card_image' => 'nullable|boolean',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
        ];

        return $request->validate($rules);
    }

    /**
     * Store a main image as database binary.
     */
    private function storeMainImage(UploadedFile $file, Dress $dress): void
    {
        $this->images->store($dress, $file, 'main');
    }

    /**
     * Store the admin's manually-cropped card image (a separate collection
     * from 'main' — see Dress::cardImage()).
     */
    private function storeCardImage(UploadedFile $file, Dress $dress): void
    {
        $this->images->store($dress, $file, 'card');
    }

    /**
     * Store multiple gallery images as database binaries.
     */
    private function storeGalleryImages(UploadedFile|array $files, Dress $dress): void
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $index => $file) {
            try {
                $this->images->store($dress, $file, 'gallery', $index + 1);
            } catch (\InvalidArgumentException $e) {
                continue; // skip invalid files
            }
        }
    }
}