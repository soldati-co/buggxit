<?php

namespace Tests\Unit;

use App\Models\Dress;
use App\Services\ImageStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DressImageUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_main_image_url_falls_back_to_placeholder_when_no_image_exists(): void
    {
        $dress = new Dress();

        $this->assertSame(asset('logo.webp'), $dress->main_image_url);
    }

    public function test_card_image_url_falls_back_to_main_image_url_when_no_crop_exists(): void
    {
        $dress = Dress::factory()->create();
        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');

        $this->assertFalse($dress->fresh()->has_card_crop);
        $this->assertSame($dress->fresh()->main_image_url, $dress->fresh()->card_image_url);
    }

    public function test_card_image_url_returns_the_dedicated_card_image_when_a_crop_exists(): void
    {
        $dress = Dress::factory()->create();
        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        $cardImage = app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('card.jpg'), 'card');

        $fresh = $dress->fresh();

        $this->assertTrue($fresh->has_card_crop);
        $this->assertSame(route('api.image.show', $cardImage->id), $fresh->card_image_url);
        $this->assertNotSame($fresh->main_image_url, $fresh->card_image_url);
    }

    public function test_has_main_image_is_false_by_default_and_true_once_a_main_image_is_stored(): void
    {
        $dress = Dress::factory()->create();
        $this->assertFalse($dress->fresh()->has_main_image);

        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');

        $this->assertTrue($dress->fresh()->has_main_image);
    }

    public function test_new_arrivals_image_url_falls_back_to_main_image_when_neither_crop_exists(): void
    {
        $dress = Dress::factory()->create();
        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');

        $fresh = $dress->fresh();
        $this->assertFalse($fresh->has_card_crop_new_arrivals);
        $this->assertSame($fresh->main_image_url, $fresh->card_image_new_arrivals_url);
    }

    public function test_new_arrivals_image_url_falls_back_to_the_card_grid_crop_when_only_that_exists(): void
    {
        $dress = Dress::factory()->create();
        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        $gridCrop = app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('grid.jpg'), 'card');

        $fresh = $dress->fresh();
        // Falling back to a deliberate crop (even the wrong shape) still
        // counts as "cropped" for object-cover purposes.
        $this->assertTrue($fresh->has_card_crop_new_arrivals);
        $this->assertSame(route('api.image.show', $gridCrop->id), $fresh->card_image_new_arrivals_url);
    }

    public function test_new_arrivals_image_url_prefers_its_own_dedicated_crop_over_the_card_grid_one(): void
    {
        $dress = Dress::factory()->create();
        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('grid.jpg'), 'card');
        $newArrivalsCrop = app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('new-arrivals.jpg'), 'card_new_arrivals');

        $fresh = $dress->fresh();
        $this->assertTrue($fresh->has_card_crop_new_arrivals);
        $this->assertSame(route('api.image.show', $newArrivalsCrop->id), $fresh->card_image_new_arrivals_url);
    }

    public function test_detail_image_url_falls_back_to_main_image_url_when_no_crop_exists(): void
    {
        $dress = Dress::factory()->create();
        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');

        $fresh = $dress->fresh();
        $this->assertFalse($fresh->has_detail_crop);
        $this->assertSame($fresh->main_image_url, $fresh->detail_image_url);
    }

    public function test_detail_image_url_returns_the_dedicated_detail_image_when_a_crop_exists(): void
    {
        $dress = Dress::factory()->create();
        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        // A card-grid crop existing should NOT leak into detail_image_url —
        // unlike New Arrivals, the detail page has no intermediate fallback.
        app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('grid.jpg'), 'card');
        $detailImage = app(ImageStorageService::class)->store($dress, UploadedFile::fake()->image('detail.jpg'), 'detail');

        $fresh = $dress->fresh();
        $this->assertTrue($fresh->has_detail_crop);
        $this->assertSame(route('api.image.show', $detailImage->id), $fresh->detail_image_url);
        $this->assertNotSame($fresh->card_image_url, $fresh->detail_image_url);
    }
}
