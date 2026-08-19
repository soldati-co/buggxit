<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DressResource;
use App\Models\Category;
use App\Models\Dress;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class HomeApiController extends Controller
{
    /**
     * How long to cache the homepage payload for, in seconds.
     * Keeps this route from hitting the DB (and the Supabase pooler) on every visit.
     */
    private const CACHE_TTL = 120;

    /**
     * How long to cache the (rarely-changing) "do these tables exist" checks.
     * These only change right after a migration, so an hour is safe.
     */
    private const SCHEMA_CACHE_TTL = 3600;

    public function index()
    {
        try {
            $payload = Cache::remember('home.api.payload', self::CACHE_TTL, function () {
                return $this->buildPayload();
            });

            return response()->json($payload);
        } catch (Throwable $e) {
            // If the DB/pooler hiccups (or the cache store itself is unreachable),
            // log it and return an empty-but-valid payload instead of a 500.
            // The homepage can render with no content far better than it can render nothing.
            Log::error('HomeApiController failed, returning empty fallback payload', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'featured' => [],
                'new_arrivals' => [],
                'categories' => [],
            ]);
        }
    }

    /**
     * Do the actual DB work. Only called on a cache miss.
     */
    private function buildPayload(): array
    {
        $tables = $this->tablesExist();

        $featuredDresses = collect();
        $newArrivals = collect();
        $activeCategories = collect();

        if ($tables['dresses']) {
            $featuredDresses = Dress::with('categories')
                ->withImageUrls()
                ->where('is_featured', true)
                ->where('status', 'active')
                ->latest()
                ->take(4)
                ->get();

            $newArrivals = Dress::with('categories')
                ->withImageUrls()
                ->where('status', 'active')
                ->latest()
                ->take(4)
                ->get();

            $activeCategories = Category::withCount('dresses')
                ->where('is_active', true)
                ->whereHas('dresses')
                ->orderBy('sort_order')
                ->get();
        }

        return [
            'featured' => DressResource::collection($featuredDresses)->resolve(),
            'new_arrivals' => DressResource::collection($newArrivals)->resolve(),
            'categories' => CategoryResource::collection($activeCategories)->resolve(),
        ];
    }

    /**
     * Cache the Schema::hasTable() lookups. These hit the DB every time otherwise,
     * and the answer never changes outside of a deploy/migration.
     *
     * @return array{dresses: bool}
     */
    private function tablesExist(): array
    {
        return Cache::remember('home.api.tables_exist', self::SCHEMA_CACHE_TTL, function () {
            return [
                'dresses' => Schema::hasTable('dresses')
                    && Schema::hasTable('categories')
                    && Schema::hasTable('category_dress'),
            ];
        });
    }
}