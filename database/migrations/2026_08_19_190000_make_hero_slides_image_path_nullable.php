<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * image_path was the original hero-slide image storage mechanism (a local
 * disk path). The app has since moved to the polymorphic Image model
 * (base64-in-Postgres, same as Dress images), and HeroSlideController no
 * longer writes to this column at all — but it was never nullable, so
 * every hero slide creation has been failing with a NOT NULL violation.
 * The column itself is kept for now (existing rows still have legacy
 * values) but is no longer required.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE hero_slides ALTER COLUMN image_path DROP NOT NULL');
            return;
        }

        Schema::table('hero_slides', function (Blueprint $table) {
            $table->string('image_path')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("UPDATE hero_slides SET image_path = '' WHERE image_path IS NULL");
            DB::statement('ALTER TABLE hero_slides ALTER COLUMN image_path SET NOT NULL');
            return;
        }

        Schema::table('hero_slides', function (Blueprint $table) {
            $table->string('image_path')->nullable(false)->change();
        });
    }
};
