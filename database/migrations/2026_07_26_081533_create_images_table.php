<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->longText('image_data'); // or $table->mediumBlob('image_data') for up to 16MB
            $table->string('image_mime', 50);
            $table->nullableUuidMorphs('imageable'); // <-- UUID compatible
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('collection')->default('default'); // e.g., 'main', 'gallery', 'hero'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
};