<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->get();
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
            'image'      => 'required|image|mimes:jpeg,png,jpg,webp,heic|max:9999048',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ]);

        $path = $request->file('image')->store('hero-slides', 'public');

        HeroSlide::create([
            'title'      => $validated['title'] ?? null,
            'headline'   => $validated['headline'] ?? null,
            'subheading' => $validated['subheading'] ?? null,
            'cta_text'   => $validated['cta_text'] ?? null,
            'cta_url'    => $validated['cta_url'] ?? null,
            'image_path' => $path,
            'alt_text'   => $validated['alt_text'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide added successfully.');
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
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp,heic|max:9999048',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ]);

        $data = $request->only([
            'title', 'headline', 'subheading', 'cta_text', 'cta_url',
            'alt_text', 'sort_order', 'is_active'
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($heroSlide->image_path) {
                Storage::disk('public')->delete($heroSlide->image_path);
            }
            $data['image_path'] = $request->file('image')->store('hero-slides', 'public');
        }

        $heroSlide->update($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->image_path) {
            Storage::disk('public')->delete($heroSlide->image_path);
        }
        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide deleted.');
    }

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