<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DressResource;
use App\Models\Category;
use App\Models\Dress;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DressApiController extends Controller
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

    private array $availableSizes = [32, 34, 36, 38, 40, 42];

    public function index(Request $request)
    {
        $query = Dress::with('categories')->where('status', 'active');

        if ($request->filled('category')) {
            $category = Category::where('slug', '=', $request->category, 'and')
                ->orWhere('id', '=', $request->category, 'or')
                ->first();

            if ($category) {
                $query->whereHas('categories', fn ($query) => $query->where('categories.id', '=', $category->id, 'and'));
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

    public function store(Request $request)
    {
        $validated = $this->validateDress($request);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['stock_quantity'] = $validated['stock_quantity'] ?? 0;
        $validated['is_taxable'] = $validated['is_taxable'] ?? true;
        $validated['requires_shipping'] = $validated['requires_shipping'] ?? true;
        $validated['is_featured'] = $validated['is_featured'] ?? false;

        unset($validated['main_image'], $validated['gallery_images']);

        $dress = Dress::create($validated);

        if ($request->hasFile('main_image')) {
            $this->storeMainImage($request->file('main_image'), $dress);
        }

        if ($request->hasFile('gallery_images')) {
            $this->storeGalleryImages($request->file('gallery_images'), $dress);
        }

        if ($request->has('category_ids')) {
            $dress->categories()->sync($request->input('category_ids', []));
        }

        return response()->json($dress->load('categories'), 201);
    }

    public function update(Request $request, Dress $dress)
    {
        $validated = $this->validateDress($request, $dress);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        unset($validated['main_image'], $validated['gallery_images']);

        if ($request->hasFile('main_image')) {
            $mainImage = $dress->mainImage()->first();
            if ($mainImage) {
                $mainImage->delete();
            }
            $this->storeMainImage($request->file('main_image'), $dress);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($dress->galleryImages()->get() as $oldImg) {
                $oldImg->delete();
            }
            $this->storeGalleryImages($request->file('gallery_images'), $dress);
        }

        $dress->update($validated);
        $dress->categories()->sync($request->input('category_ids', []));

        return response()->json($dress->load('categories'));
    }

    public function destroy(Dress $dress)
    {
        foreach ($dress->images()->get() as $img) {
            $img->delete();
        }
        Dress::destroy($dress->id);

        return response()->json(['message' => 'Dress deleted successfully.']);
    }

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
                    if (in_array($value, $standardColorKeys)) {
                        return;
                    }
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
            'main_image' => 'nullable|file|max:9048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'file|max:9048',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
        ];

        return $request->validate($rules);
    }

    private function storeMainImage(UploadedFile $file, Dress $dress): void
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

    private function storeGalleryImages(UploadedFile|array $files, Dress $dress): void
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $index => $file) {
            $mime = $file->getMimeType();
            if (!in_array($mime, self::ALLOWED_IMAGE_MIMETYPES)) {
                continue;
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
