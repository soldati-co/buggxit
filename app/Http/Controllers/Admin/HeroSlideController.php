<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;

class HeroSlideController extends Controller
{
    /**
     * Display a listing of hero slides.
     */
    public function index()
    {
        $slides = HeroSlide::ordered()->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    /**
     * Show the form for creating a new hero slide.
     */
    public function create()
    {
        return view('admin.hero-slides.create');
    }

    /**
     * Store a newly created hero slide.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'headline'   => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:1000',
            'cta_text'   => 'nullable|string|max:255',
            'cta_url'    => 'nullable|url|max:2048',
            'image'      => 'required|image|max:9999048',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ]);

        try {
            // Process image and get binary + mime
            $manager = new ImageManager();
            $img = $manager->make($request->file('image'));
            $img->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $encoded = (string) $img->encode('webp', 80); // get binary string

            // Create slide (no image_path needed)
            $slide = HeroSlide::create([
                'title'      => $validated['title'] ?? null,
                'headline'   => $validated['headline'] ?? null,
                'subheading' => $validated['subheading'] ?? null,
                'cta_text'   => $validated['cta_text'] ?? null,
                'cta_url'    => $validated['cta_url'] ?? null,
                'alt_text'   => $validated['alt_text'] ?? null,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active'  => $request->boolean('is_active', true),
            ]);

            // Save the image as binary via polymorphic relationship
            $slide->image()->create([
                'image_data' => $encoded,
                'image_mime' => 'image/webp',
                'collection' => 'hero',
                'sort_order' => 0,
            ]);

            Log::info('HeroSlide created with database image', ['id' => $slide->id]);

            return redirect()->route('admin.hero-slides.index')
                ->with('success', 'Slide added successfully.');

        } catch (\Exception $e) {
            Log::error('HeroSlide store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Failed to create slide: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified hero slide.
     */
    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.edit', compact('heroSlide'));
    }

    /**
     * Update the specified hero slide.
     */
    public function update(Request $request, HeroSlide $heroSlide)
    {
        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'headline'   => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:1000',
            'cta_text'   => 'nullable|string|max:255',
            'cta_url'    => 'nullable|url|max:2048',
            'image'      => 'nullable|image|max:9999048',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ]);

        $data = $request->only([
            'title', 'headline', 'subheading', 'cta_text', 'cta_url',
            'alt_text', 'sort_order', 'is_active'
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        try {
            if ($request->hasFile('image')) {
                // Delete old image record from database
                $heroSlide->image()->delete();

                // Process and store new image
                $manager = new ImageManager();
                $img = $manager->make($request->file('image'));
                $img->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $encoded = (string) $img->encode('webp', 80);

                $heroSlide->image()->create([
                    'image_data' => $encoded,
                    'image_mime' => 'image/webp',
                    'collection' => 'hero',
                    'sort_order' => 0,
                ]);
            }

            $heroSlide->update($data);
            Log::info('HeroSlide updated', ['id' => $heroSlide->id]);

            return redirect()->route('admin.hero-slides.index')
                ->with('success', 'Slide updated successfully.');

        } catch (\Exception $e) {
            Log::error('HeroSlide update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified hero slide.
     */
    public function destroy(HeroSlide $heroSlide)
    {
        try {
            // Polymorphic images are automatically deleted if you set up cascading or soft deletes?
            // For now, delete the associated image record manually.
            $heroSlide->image()->delete();
            $heroSlide->delete();

            Log::info('HeroSlide deleted', ['id' => $heroSlide->id]);

            return redirect()->route('admin.hero-slides.index')
                ->with('success', 'Slide deleted.');
        } catch (\Exception $e) {
            Log::error('HeroSlide delete error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Delete failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the sort order of hero slides (AJAX).
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:hero_slides,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->order as $item) {
            HeroSlide::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}