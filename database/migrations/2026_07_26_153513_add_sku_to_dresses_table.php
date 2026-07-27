<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add nullable first
        Schema::table('dresses', function (Blueprint $table) {
            $table->string('sku', 50)->nullable();
        });

        // Generate a SKU for existing rows (customize if needed)
        \DB::statement("UPDATE dresses SET sku = CONCAT('DRESS-', id) WHERE sku IS NULL");

        // Make it NOT NULL and unique
        Schema::table('dresses', function (Blueprint $table) {
            $table->string('sku', 50)->nullable(false)->unique()->change();
        });

        // Drop old columns if they exist
        if (Schema::hasColumn('dresses', 'sku_prefix')) {
            Schema::table('dresses', fn (Blueprint $table) => $table->dropColumn('sku_prefix'));
        }
        if (Schema::hasColumn('dresses', 'custom_sku')) {
            Schema::table('dresses', fn (Blueprint $table) => $table->dropColumn('custom_sku'));
        }
    }

    public function down(): void
    {
        Schema::table('dresses', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropColumn('sku');

            $table->string('sku_prefix', 50)->nullable();
            $table->string('custom_sku', 50)->nullable();
        });
    }
};