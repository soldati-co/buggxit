<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::ordered()->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.hero-slides.create');
    }

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
            // Process image using Intervention v4
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image'))
                ->cover(1920, 1080)
                ->toWebp(80);                    // quality 80
            $encoded = (string) $image;           // get binary string

            // Create slide
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

            // Save image binary in polymorphic table
            $slide->image()->create([
                'image_data' => $encoded,
                'image_mime' => 'image/webp',
                'collection' => 'hero',
                'sort_order' => 0,
            ]);

            Log::info('HeroSlide created with DB image', ['id' => $slide->id]);

            return redirect()->route('admin.hero-slides.index')
                ->with('success', 'Slide added successfully.');
        } catch (\Exception $e) {
            Log::error('HeroSlide store error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create slide: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.edit', compact('heroSlide'));
    }

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
                // Delete old database image record
                $heroSlide->image()->delete();

                // Process new image
                $manager = new ImageManager(new Driver());
                $newImage = $manager->read($request->file('image'))
                    ->cover(1920, 1080)
                    ->toWebp(80);
                $encoded = (string) $newImage;

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
            Log::error('HeroSlide update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(HeroSlide $heroSlide)
    {
        try {
            // Delete image record (binary removed from DB)
            $heroSlide->image()->delete();
            $heroSlide->delete();

            Log::info('HeroSlide deleted', ['id' => $heroSlide->id]);
            return redirect()->route('admin.hero-slides.index')
                ->with('success', 'Slide deleted.');
        } catch (\Exception $e) {
            Log::error('HeroSlide delete error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Delete failed: ' . $e->getMessage()]);
        }
    }

    // updateOrder unchanged
}