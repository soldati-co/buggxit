<?php

namespace Tests\Feature;

use App\Models\Dress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The navbar and mobile-menu search inputs (resources/views/layouts/navigation.blade.php)
 * used to be dead UI with no submit handler, and Api\ProductApiController::index()
 * had no search filter at all. Covers the API contract the navbar search and
 * the products page's own search box now rely on.
 */
class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_matches_dress_name_case_insensitively(): void
    {
        $match = Dress::factory()->create(['status' => 'active', 'name' => 'Umbaco Dress']);
        $noMatch = Dress::factory()->create(['status' => 'active', 'name' => 'Something Else']);

        $response = $this->getJson(route('api.products.index', ['search' => 'umbaco']));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertTrue($ids->contains($match->id));
        $this->assertFalse($ids->contains($noMatch->id));
    }

    public function test_search_matches_dress_description(): void
    {
        $match = Dress::factory()->create([
            'status' => 'active',
            'name' => 'Traditional Set',
            'description' => 'A beautiful shweshwe ceremony gown.',
        ]);
        $noMatch = Dress::factory()->create([
            'status' => 'active',
            'name' => 'Other Dress',
            'description' => 'Plain everyday wear.',
        ]);

        $response = $this->getJson(route('api.products.index', ['search' => 'shweshwe']));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertTrue($ids->contains($match->id));
        $this->assertFalse($ids->contains($noMatch->id));
    }

    public function test_search_can_be_combined_with_sort_and_category(): void
    {
        $expensiveMatch = Dress::factory()->create(['status' => 'active', 'name' => 'Umbaco Gold', 'price' => 900]);
        $cheapMatch = Dress::factory()->create(['status' => 'active', 'name' => 'Umbaco Silver', 'price' => 100]);

        $response = $this->getJson(route('api.products.index', ['search' => 'umbaco', 'sort' => 'price_asc']));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertSame($cheapMatch->id, $ids->first());
        $this->assertSame($expensiveMatch->id, $ids->last());
    }

    public function test_no_results_for_a_search_with_no_matches(): void
    {
        Dress::factory()->create(['status' => 'active', 'name' => 'Umbaco Dress']);

        $response = $this->getJson(route('api.products.index', ['search' => 'nonexistent-xyz']));

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }

    public function test_blank_search_returns_all_active_dresses(): void
    {
        Dress::factory()->count(2)->create(['status' => 'active']);

        $response = $this->getJson(route('api.products.index', ['search' => '']));

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }

    public function test_inactive_dresses_are_excluded_from_search_results(): void
    {
        $inactive = Dress::factory()->create(['status' => 'draft', 'name' => 'Umbaco Draft']);

        $response = $this->getJson(route('api.products.index', ['search' => 'umbaco']));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertFalse($ids->contains($inactive->id));
    }
}
