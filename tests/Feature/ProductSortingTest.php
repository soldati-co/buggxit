<?php

namespace Tests\Feature;

use App\Models\Dress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The "Sort by" dropdown on /products (resources/views/products/index.blade.php)
 * used to be dead UI — plain <a href="#"> links with no click handler — and
 * Api\ProductApiController::index() ignored any sort input, always ordering
 * by ->latest(). Covers the API contract the frontend now relies on.
 */
class ProductSortingTest extends TestCase
{
    use RefreshDatabase;

    public function test_defaults_to_latest_first_when_no_sort_given(): void
    {
        $older = Dress::factory()->create(['status' => 'active', 'created_at' => now()->subDay()]);
        $newer = Dress::factory()->create(['status' => 'active', 'created_at' => now()]);

        $response = $this->getJson(route('api.products.index'));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertSame($newer->id, $ids->first());
        $this->assertSame($older->id, $ids->last());
    }

    public function test_sort_price_asc_orders_cheapest_first(): void
    {
        $expensive = Dress::factory()->create(['status' => 'active', 'price' => 900]);
        $cheap = Dress::factory()->create(['status' => 'active', 'price' => 100]);

        $response = $this->getJson(route('api.products.index', ['sort' => 'price_asc']));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertSame($cheap->id, $ids->first());
        $this->assertSame($expensive->id, $ids->last());
    }

    public function test_sort_price_desc_orders_priciest_first(): void
    {
        $expensive = Dress::factory()->create(['status' => 'active', 'price' => 900]);
        $cheap = Dress::factory()->create(['status' => 'active', 'price' => 100]);

        $response = $this->getJson(route('api.products.index', ['sort' => 'price_desc']));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertSame($expensive->id, $ids->first());
        $this->assertSame($cheap->id, $ids->last());
    }

    public function test_unrecognized_sort_value_falls_back_to_latest(): void
    {
        $older = Dress::factory()->create(['status' => 'active', 'created_at' => now()->subDay()]);
        $newer = Dress::factory()->create(['status' => 'active', 'created_at' => now()]);

        $response = $this->getJson(route('api.products.index', ['sort' => 'not-a-real-sort']));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertSame($newer->id, $ids->first());
    }
}
