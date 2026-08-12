<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The live `dresses` table is missing `sku` even though a prior migration
     * (2026_07_26_153513_add_sku_to_dresses_table) is recorded as having run —
     * the database appears to have been restored from a snapshot that predates
     * that migration's actual effect, while the migrations bookkeeping table
     * wasn't reset to match. AdminDressController's validation requires `sku`
     * (required|unique), so every admin dress create/update currently 500s.
     * This is a guarded re-application: safe no-op if `sku` already exists.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('dresses', 'sku')) {
            Schema::table('dresses', function (Blueprint $table) {
                $table->string('sku', 50)->nullable();
            });

            if (Schema::hasColumn('dresses', 'custom_sku')) {
                DB::statement("UPDATE dresses SET sku = custom_sku WHERE sku IS NULL AND custom_sku IS NOT NULL");
            }

            DB::statement("UPDATE dresses SET sku = CONCAT('DRESS-', id) WHERE sku IS NULL");

            Schema::table('dresses', function (Blueprint $table) {
                $table->string('sku', 50)->nullable(false)->unique()->change();
            });
        }

        if (Schema::hasColumn('dresses', 'sku_prefix')) {
            Schema::table('dresses', fn (Blueprint $table) => $table->dropColumn('sku_prefix'));
        }

        if (Schema::hasColumn('dresses', 'custom_sku')) {
            Schema::table('dresses', fn (Blueprint $table) => $table->dropColumn('custom_sku'));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // sku is a real, load-bearing column (admin validation depends on it);
        // reverting would immediately re-break admin dress editing. Intentionally left empty.
    }
};
