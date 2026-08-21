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
}
