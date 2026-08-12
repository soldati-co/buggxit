<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            // Use string primary key for SQLite (tests) to allow UUIDs, integer id otherwise
            // (see 2026_08_12_020007_convert_hero_slides_to_uuid for the Postgres-side conversion).
            if (DB::getDriverName() === 'sqlite') {
                $table->string('id')->primary();
            } else {
                $table->id();
            }
            $table->string('title')->nullable();
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hero_slides');
    }
};