<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarded catch-up migration: `category_dress` already exists on
     * production (see `create_categories_table` for the history gap
     * context). No-op there; sets up the pivot table for
     * `Dress::categories()`/`Category::dresses()` on a fresh install/CI
     * database. Runs after `convert_dresses_to_uuid` so the `dress_id`
     * foreign key type matches.
     */
    public function up(): void
    {
        if (Schema::hasTable('category_dress')) {
            return;
        }

        Schema::create('category_dress', function (Blueprint $table) {
            $table->uuid('category_id');
            $table->uuid('dress_id');
            $table->timestamps();

            $table->primary(['category_id', 'dress_id']);
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->foreign('dress_id')->references('id')->on('dresses')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Dropping a live-data-bearing table is unsafe to automate; intentionally left empty.
    }
};
