<?php

namespace Tests\Feature;

use App\Models\Dress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Dress::resolveRouteBinding falls back from slug to uuid-primary-key lookup.
 * The uuid branch used to run unconditionally, so a non-existent slug that
 * also isn't a valid UUID (e.g. "not-a-real-slug") reached where('id', $value)
 * against a uuid-typed Postgres column and threw a raw QueryException (500)
 * instead of a clean 404. Covers both API routes that implicitly bind Dress.
 */
class DressRouteBindingTest extends TestCase
{
    use RefreshDatabase;

    public function test_malformed_product_identifier_returns_404_not_a_server_error(): void
    {
        $this->get(route('api.products.show', ['dress' => 'not-a-real-slug']))
            ->assertNotFound();
    }

    public function test_malformed_dress_identifier_returns_404_not_a_server_error(): void
    {
        $this->get(route('api.dresses.show', ['dress' => 'not-a-real-slug']))
            ->assertNotFound();
    }

    public function test_valid_slug_still_resolves(): void
    {
        $dress = Dress::factory()->create(['status' => 'active']);

        $this->get(route('api.products.show', ['dress' => $dress->slug]))
            ->assertOk();
    }
}
