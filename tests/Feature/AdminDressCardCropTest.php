<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Dress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Coverage for the admin's manual card-crop feature: a separate 'card'
 * collection image (Dress::cardImage()) used for grid-card display, kept
 * distinct from the 'main' image so the product detail page still shows the
 * true, uncropped photo. See CartService et al for the sibling convention
 * this follows (delete-before-replace, optional-by-default).
 */
class AdminDressCardCropTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        $category = Category::create([
            'name' => 'Traditional',
            'slug' => 'traditional',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        return array_merge([
            'name' => 'Phenduka Dress',
            'sku' => 'DRESS-'.uniqid(),
            'description' => 'A ceremony dress.',
            'price' => 2400,
            'turnaround_time' => '2 weeks',
            'expected_delivery' => '2 days',
            'sizes' => [34, 36],
            'colors' => ['red', 'blue'],
            'status' => 'active',
            'category_ids' => [$category->id],
        ], $overrides);
    }

    public function test_admin_can_view_the_create_page_with_the_crop_partial(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dresses.create'));

        $response->assertOk();
        $response->assertSee('Crop for Card');
    }

    public function test_admin_can_view_the_edit_page_for_a_dress_with_no_images_yet(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dresses.edit', $dress));

        $response->assertOk();
        $response->assertSee('Crop for Card');
    }

    public function test_admin_can_view_the_edit_page_for_a_dress_with_a_main_image_and_card_crop(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('card.jpg'), 'card');

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dresses.edit', $dress));

        $response->assertOk();
        $response->assertSee('Edit Card Crop');
    }

    public function test_admin_can_upload_a_card_image_when_creating_a_dress(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
            'card_image' => UploadedFile::fake()->image('card.jpg'),
        ]));

        $response->assertRedirect(route('admin.dresses.index'));
        $dress = Dress::firstOrFail();

        $this->assertTrue($dress->has_main_image);
        $this->assertTrue($dress->has_card_crop);
        $this->assertNotSame($dress->main_image_url, $dress->card_image_url);
    }

    public function test_admin_can_add_a_card_crop_to_an_existing_dress_without_re_uploading_main_image(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        $existingMainImageId = app(\App\Services\ImageStorageService::class)
            ->store($dress, UploadedFile::fake()->image('main.jpg'), 'main')->id;

        $response = $this->actingAs($admin, 'admin')->put(route('admin.dresses.update', $dress), $this->validPayload([
            'card_image' => UploadedFile::fake()->image('card.jpg'),
        ]));

        $response->assertRedirect(route('admin.dresses.index'));
        $dress->refresh();

        $this->assertTrue($dress->has_card_crop);
        // The main image is untouched — same underlying Image row.
        $this->assertSame($existingMainImageId, $dress->mainImage()->first()->id);
    }

    public function test_updating_card_image_replaces_the_old_one_not_appends(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('card-v1.jpg'), 'card');

        $this->actingAs($admin, 'admin')->put(route('admin.dresses.update', $dress), $this->validPayload([
            'card_image' => UploadedFile::fake()->image('card-v2.jpg'),
        ]))->assertRedirect(route('admin.dresses.index'));

        $this->assertSame(1, $dress->fresh()->images()->where('collection', 'card')->count());
    }

    public function test_admin_can_remove_an_existing_card_crop_via_remove_card_image_flag(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('card.jpg'), 'card');

        $this->actingAs($admin, 'admin')->put(route('admin.dresses.update', $dress), $this->validPayload([
            'remove_card_image' => '1',
        ]))->assertRedirect(route('admin.dresses.index'));

        $dress->refresh();
        $this->assertFalse($dress->has_card_crop);
        $this->assertSame($dress->main_image_url, $dress->card_image_url);
    }

    public function test_dress_can_be_saved_without_a_card_image_and_falls_back_safely(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
        ]));

        $response->assertRedirect(route('admin.dresses.index'));
        $dress = Dress::firstOrFail();

        $this->assertFalse($dress->has_card_crop);
        $this->assertSame($dress->main_image_url, $dress->card_image_url);
    }

    public function test_card_image_rejects_non_jpeg_uploads(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
            'card_image' => UploadedFile::fake()->image('card.png'),
        ]));

        $response->assertSessionHasErrors('card_image');
        $this->assertSame(0, Dress::count());
    }

    public function test_dress_resource_exposes_card_image_url_and_has_card_crop(): void
    {
        $dress = Dress::factory()->create(['status' => 'active']);
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('card.jpg'), 'card');

        $response = $this->getJson(route('api.products.index'));

        $response->assertOk();
        $item = collect($response->json('data'))->firstWhere('id', $dress->id);
        $this->assertNotNull($item);
        $this->assertTrue($item['has_card_crop']);
        $this->assertNotSame($item['main_image_url'], $item['card_image_url']);
    }
}
