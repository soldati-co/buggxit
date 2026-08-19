<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Services\ImageStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HeroSlideController extends Controller
{
    public function __construct(private ImageStorageService $images)
    {
    }

    public function index()
    {
        $slides = HeroSlide::ordered()->with('image')->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.hero-slides.create');
    }

    public function store(Request $request)
    {
        if ($oversizeError = $this->oversizeUploadError($request, 'image')) {
            return back()->withErrors(['error' => $oversizeError])->withInput();
        }

        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'headline'   => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:1000',
            'cta_text'   => 'nullable|string|max:255',
            'cta_url'    => 'nullable|url|max:2048',
            'image'      => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp,avif|max:9999048',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ]);

        try {
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

            // Store the upload as-is (base64-encoded, same as dress images
            // via ImageStorageService) — no GD/Intervention resizing or
            // format conversion, since GD isn't available on this server.
            $this->images->store($slide, $request->file('image'), 'hero');

            Log::info('HeroSlide created with DB image', ['id' => $slide->id]);

            return redirect()->route('admin.hero-slides.index')
                ->with('success', 'Slide added successfully.');
        } catch (\Throwable $e) {
            Log::error('HeroSlide store error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Failed to create slide: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.edit', compact('heroSlide'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        if ($oversizeError = $this->oversizeUploadError($request, 'image')) {
            return back()->withErrors(['error' => $oversizeError])->withInput();
        }

        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'headline'   => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:1000',
            'cta_text'   => 'nullable|string|max:255',
            'cta_url'    => 'nullable|url|max:2048',
            'image'      => 'nullable|file|mimes:jpg,jpeg,png,gif,bmp,webp,avif|max:9999048',
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
                $this->images->store($heroSlide, $request->file('image'), 'hero');
            }

            $heroSlide->update($data);
            Log::info('HeroSlide updated', ['id' => $heroSlide->id]);

            return redirect()->route('admin.hero-slides.index')
                ->with('success', 'Slide updated successfully.');
        } catch (\Throwable $e) {
            Log::error('HeroSlide update error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
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

    /**
     * Persist the new drag-and-drop order for hero slides.
     * Expects: { order: [{ id: string, sort_order: int }, ...] }
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:hero_slides,id',
            'order.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['order'] as $entry) {
                    HeroSlide::whereKey($entry['id'])->update(['sort_order' => $entry['sort_order']]);
                }
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('HeroSlide updateOrder error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update order.'], 500);
        }
    }

    /**
     * PHP silently drops an upload that exceeds post_max_size — no $_FILES
     * entry is populated at all, so Laravel's own validation just sees a
     * missing field and reports "the image field is required", which is
     * misleading for what's actually a size problem. Detect that specific
     * case (a POST body was clearly larger than what PHP accepted, but the
     * expected file is missing) and return a clear, accurate message instead.
     */
    private function oversizeUploadError(Request $request, string $field): ?string
    {
        if ($request->hasFile($field)) {
            return null;
        }

        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        $postMaxBytes = $this->iniBytes(ini_get('post_max_size'));

        if ($contentLength > 0 && $postMaxBytes > 0 && $contentLength > $postMaxBytes) {
            return sprintf(
                'That image is too large for the server to accept (limit is %s). Please use a smaller file or compress it first.',
                ini_get('post_max_size')
            );
        }

        return null;
    }

    private function iniBytes(string $value): int
    {
        $value = trim($value);
        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return (int) match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => $number,
        };
    }
}
