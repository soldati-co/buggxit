<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarded catch-up migration: adds columns that exist on the live
     * `dresses` table but were never captured by any tracked migration
     * (confirmed via a live information_schema introspection). No-op on
     * production (already has every column); fills the gap for a fresh
     * install/CI database so it matches what `App\Models\Dress` and
     * `AdminDressController`'s validation actually expect.
     */
    public function up(): void
    {
        Schema::table('dresses', function (Blueprint $table) {
            if (! Schema::hasColumn('dresses', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (! Schema::hasColumn('dresses', 'compare_at_price')) {
                $table->decimal('compare_at_price', 10, 2)->nullable()->after('price');
            }
            if (! Schema::hasColumn('dresses', 'stock_quantity')) {
                $table->integer('stock_quantity')->default(0)->after('compare_at_price');
            }
            if (! Schema::hasColumn('dresses', 'low_stock_threshold')) {
                $table->integer('low_stock_threshold')->nullable()->after('stock_quantity');
            }
            if (! Schema::hasColumn('dresses', 'is_taxable')) {
                $table->boolean('is_taxable')->default(true)->after('is_featured');
            }
            if (! Schema::hasColumn('dresses', 'requires_shipping')) {
                $table->boolean('requires_shipping')->default(true)->after('is_taxable');
            }
            if (! Schema::hasColumn('dresses', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (! Schema::hasColumn('dresses', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
        });

        // slug must be unique and populated; backfill from name, then add the unique index.
        if (Schema::hasColumn('dresses', 'slug')) {
            $needsBackfill = \Illuminate\Support\Facades\DB::table('dresses')->whereNull('slug')->exists();
            if ($needsBackfill) {
                $rows = \Illuminate\Support\Facades\DB::table('dresses')->whereNull('slug')->get(['id', 'name']);
                foreach ($rows as $row) {
                    $base = \Illuminate\Support\Str::slug($row->name) ?: 'dress';
                    $slug = $base;
                    $suffix = 1;
                    while (\Illuminate\Support\Facades\DB::table('dresses')->where('slug', $slug)->exists()) {
                        $slug = $base.'-'.(++$suffix);
                    }
                    \Illuminate\Support\Facades\DB::table('dresses')->where('id', $row->id)->update(['slug' => $slug]);
                }
            }

            $indexes = collect(Schema::getIndexes('dresses'))->pluck('name');
            if (! $indexes->contains('dresses_slug_unique')) {
                Schema::table('dresses', function (Blueprint $table) {
                    $table->string('slug')->nullable(false)->unique()->change();
                });
            }
        }
    }

    public function down(): void
    {
        // Additive-only; reverting would risk losing populated data. Intentionally left empty.
    }
};
