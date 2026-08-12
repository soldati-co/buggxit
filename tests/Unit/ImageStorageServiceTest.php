<?php

namespace Tests\Unit;

use App\Models\Dress;
use App\Models\HeroSlide;
use App\Services\ImageStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * ImageController::show() always base64_decode()s `image_data`. Before this
 * fix, HeroSlideController wrote Intervention's raw encoded bytes directly
 * (no base64_encode()) while AdminDressController correctly did
 * base64_encode() first — a real encode/decode mismatch that would corrupt
 * every hero-slide image. These tests prove the round-trip is byte-exact
 * for both write paths through the single ImageStorageService.
 */
class ImageStorageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_round_trips_an_uploaded_file_exactly(): void
    {
        $dress = Dress::factory()->create();
        $file = UploadedFile::fake()->image('photo.jpg', 20, 20);
        $original = file_get_contents($file->getRealPath());

        $image = app(ImageStorageService::class)->store($dress, $file, 'main');

        $this->assertSame($original, base64_decode($image->image_data));
    }

    public function test_store_from_binary_round_trips_raw_bytes_exactly(): void
    {
        $slide = HeroSlide::create([
            'title' => 'Test Slide',
            'image_path' => 'unused.webp',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        // Simulates Intervention's encoded webp output: arbitrary binary,
        // not itself base64 text.
        $rawWebpBytes = random_bytes(512);

        $image = app(ImageStorageService::class)->storeFromBinary($slide, $rawWebpBytes, 'image/webp', 'hero');

        $this->assertSame($rawWebpBytes, base64_decode($image->image_data));
        $this->assertSame('image/webp', $image->image_mime);
    }

    public function test_hero_slide_image_serves_the_exact_original_bytes_over_http(): void
    {
        $slide = HeroSlide::create([
            'title' => 'Test Slide',
            'image_path' => 'unused.webp',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $rawWebpBytes = random_bytes(512);
        app(ImageStorageService::class)->storeFromBinary($slide, $rawWebpBytes, 'image/webp', 'hero');

        $response = $this->get(route('api.image.show', $slide->image->id));

        $response->assertOk();
        $this->assertSame($rawWebpBytes, $response->getContent());
        $response->assertHeader('Content-Type', 'image/webp');
    }

    public function test_store_rejects_unsupported_mimetypes(): void
    {
        $dress = Dress::factory()->create();
        $file = UploadedFile::fake()->create('script.php', 1, 'application/x-php');

        $this->expectException(\InvalidArgumentException::class);

        app(ImageStorageService::class)->store($dress, $file, 'main');
    }
}
