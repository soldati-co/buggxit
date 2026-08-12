<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarded catch-up migration: `categories` already exists on production
     * (created by a migration file that no longer exists in this repo — see
     * the sibling `create_category_dress_table` migration for context). This
     * is a no-op there; it exists so a fresh install/CI database gets the
     * table too, since `App\Models\Category` and every category/dress
     * controller depend on it.
     */
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            return;
        }

        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->uuid('parent_id')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Dropping a live-data-bearing table is unsafe to automate; intentionally left empty.
    }
};
