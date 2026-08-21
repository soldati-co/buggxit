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
        $response->assertSee('Card Grid');
        $response->assertSee('New Arrivals');
        $response->assertSee('Product Page');
    }

    public function test_admin_can_view_the_edit_page_for_a_dress_with_no_images_yet(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dresses.edit', $dress));

        $response->assertOk();
        $response->assertSee('Card Grid');
        $response->assertSee('New Arrivals');
        $response->assertSee('Product Page');
    }

    public function test_admin_can_view_the_edit_page_for_a_dress_with_a_main_image_and_card_crop(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('card.jpg'), 'card');

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dresses.edit', $dress));

        $response->assertOk();
        $response->assertSee('Edit Crop');
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

    /**
     * The hard server-side backstop: this is exactly the shape (923x1600,
     * ratio ~0.58) actually found on a live dress's card crop that caused
     * the subject to be sliced off once cards switched to object-cover —
     * the tool's client-side warning can be dismissed, so this must reject
     * outright regardless of what the browser did.
     */
    public function test_card_image_rejects_a_badly_non_square_crop(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
            'card_image' => UploadedFile::fake()->image('card.jpg', 923, 1600),
        ]));

        $response->assertSessionHasErrors('card_image');
        $this->assertSame(0, Dress::count());
    }

    public function test_card_image_new_arrivals_rejects_a_badly_non_square_crop(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
            'card_image_new_arrivals' => UploadedFile::fake()->image('na.jpg', 800, 1600),
        ]));

        $response->assertSessionHasErrors('card_image_new_arrivals');
        $this->assertSame(0, Dress::count());
    }

    public function test_detail_image_rejects_a_badly_non_square_crop(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
            'detail_image' => UploadedFile::fake()->image('detail.jpg', 700, 1600),
        ]));

        $response->assertSessionHasErrors('detail_image');
        $this->assertSame(0, Dress::count());
    }

    public function test_card_image_accepts_a_nearly_square_crop_within_tolerance(): void
    {
        $admin = Admin::factory()->create();

        // 1000x1150 => ratio 0.87, within the 0.2 tolerance band.
        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
            'card_image' => UploadedFile::fake()->image('card.jpg', 1000, 1150),
        ]));

        $response->assertRedirect(route('admin.dresses.index'));
        $this->assertTrue(Dress::firstOrFail()->has_card_crop);
    }

    public function test_admin_can_upload_a_new_arrivals_crop_independently_of_the_card_grid_crop(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
            'card_image' => UploadedFile::fake()->image('grid.jpg'),
            'card_image_new_arrivals' => UploadedFile::fake()->image('new-arrivals.jpg'),
        ]));

        $response->assertRedirect(route('admin.dresses.index'));
        $dress = Dress::firstOrFail();

        $this->assertTrue($dress->has_card_crop);
        $this->assertTrue($dress->has_card_crop_new_arrivals);
        $this->assertNotSame($dress->card_image_url, $dress->card_image_new_arrivals_url);
    }

    public function test_updating_new_arrivals_crop_replaces_the_old_one_not_appends(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('na-v1.jpg'), 'card_new_arrivals');

        $this->actingAs($admin, 'admin')->put(route('admin.dresses.update', $dress), $this->validPayload([
            'card_image_new_arrivals' => UploadedFile::fake()->image('na-v2.jpg'),
        ]))->assertRedirect(route('admin.dresses.index'));

        $this->assertSame(1, $dress->fresh()->images()->where('collection', 'card_new_arrivals')->count());
    }

    public function test_admin_can_remove_a_new_arrivals_crop_and_it_falls_back_to_the_card_grid_crop(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('grid.jpg'), 'card');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('na.jpg'), 'card_new_arrivals');

        $this->actingAs($admin, 'admin')->put(route('admin.dresses.update', $dress), $this->validPayload([
            'remove_card_image_new_arrivals' => '1',
        ]))->assertRedirect(route('admin.dresses.index'));

        $dress->refresh();
        // Falls back to the Card Grid crop, not all the way to the raw image.
        $this->assertTrue($dress->has_card_crop_new_arrivals);
        $this->assertSame($dress->card_image_url, $dress->card_image_new_arrivals_url);
    }

    public function test_removing_main_image_crops_are_independent_of_each_other(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('grid.jpg'), 'card');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('na.jpg'), 'card_new_arrivals');

        $this->actingAs($admin, 'admin')->put(route('admin.dresses.update', $dress), $this->validPayload([
            'remove_card_image' => '1',
        ]))->assertRedirect(route('admin.dresses.index'));

        $dress->refresh();
        $this->assertFalse($dress->has_card_crop);
        // The New Arrivals crop is untouched by removing the Card Grid one.
        $this->assertSame(1, $dress->images()->where('collection', 'card_new_arrivals')->count());
    }

    public function test_admin_can_upload_a_detail_crop_independently_of_the_card_crops(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
            'card_image' => UploadedFile::fake()->image('grid.jpg'),
            'detail_image' => UploadedFile::fake()->image('detail.jpg'),
        ]));

        $response->assertRedirect(route('admin.dresses.index'));
        $dress = Dress::firstOrFail();

        $this->assertTrue($dress->has_card_crop);
        $this->assertTrue($dress->has_detail_crop);
        $this->assertNotSame($dress->card_image_url, $dress->detail_image_url);
    }

    public function test_admin_can_add_a_detail_crop_to_an_existing_dress_without_re_uploading_main_image(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        $existingMainImageId = app(\App\Services\ImageStorageService::class)
            ->store($dress, UploadedFile::fake()->image('main.jpg'), 'main')->id;

        $response = $this->actingAs($admin, 'admin')->put(route('admin.dresses.update', $dress), $this->validPayload([
            'detail_image' => UploadedFile::fake()->image('detail.jpg'),
        ]));

        $response->assertRedirect(route('admin.dresses.index'));
        $dress->refresh();

        $this->assertTrue($dress->has_detail_crop);
        $this->assertSame($existingMainImageId, $dress->mainImage()->first()->id);
    }

    public function test_updating_detail_image_replaces_the_old_one_not_appends(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('detail-v1.jpg'), 'detail');

        $this->actingAs($admin, 'admin')->put(route('admin.dresses.update', $dress), $this->validPayload([
            'detail_image' => UploadedFile::fake()->image('detail-v2.jpg'),
        ]))->assertRedirect(route('admin.dresses.index'));

        $this->assertSame(1, $dress->fresh()->images()->where('collection', 'detail')->count());
    }

    public function test_admin_can_remove_a_detail_crop_and_it_falls_back_directly_to_the_main_image(): void
    {
        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create();
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        // Even with a card-grid crop present, removing the detail crop must
        // NOT fall back to it — the detail page has no intermediate fallback.
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('grid.jpg'), 'card');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('detail.jpg'), 'detail');

        $this->actingAs($admin, 'admin')->put(route('admin.dresses.update', $dress), $this->validPayload([
            'remove_detail_image' => '1',
        ]))->assertRedirect(route('admin.dresses.index'));

        $dress->refresh();
        $this->assertFalse($dress->has_detail_crop);
        $this->assertSame($dress->main_image_url, $dress->detail_image_url);
    }

    public function test_detail_image_rejects_non_jpeg_uploads(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dresses.store'), $this->validPayload([
            'main_image' => UploadedFile::fake()->image('main.jpg'),
            'detail_image' => UploadedFile::fake()->image('detail.png'),
        ]));

        $response->assertSessionHasErrors('detail_image');
        $this->assertSame(0, Dress::count());
    }

    public function test_dress_resource_exposes_detail_crop_fields(): void
    {
        $dress = Dress::factory()->create(['status' => 'active']);
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('detail.jpg'), 'detail');

        $response = $this->getJson(route('api.products.show', $dress));

        $response->assertOk();
        $data = $response->json('data');
        $this->assertTrue($data['has_detail_crop']);
        $this->assertNotSame($data['main_image_url'], $data['detail_image_url']);
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

    public function test_dress_resource_exposes_new_arrivals_crop_fields(): void
    {
        $dress = Dress::factory()->create(['status' => 'active']);
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('main.jpg'), 'main');
        app(\App\Services\ImageStorageService::class)->store($dress, UploadedFile::fake()->image('na.jpg'), 'card_new_arrivals');

        $response = $this->getJson(route('api.products.index'));

        $response->assertOk();
        $item = collect($response->json('data'))->firstWhere('id', $dress->id);
        $this->assertNotNull($item);
        $this->assertTrue($item['has_card_crop_new_arrivals']);
        $this->assertNotSame($item['main_image_url'], $item['card_image_new_arrivals_url']);
    }
}
