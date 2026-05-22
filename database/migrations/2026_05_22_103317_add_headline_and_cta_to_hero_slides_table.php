<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->string('headline')->nullable()->after('title');
            $table->text('subheading')->nullable()->after('headline');
            $table->string('cta_text')->nullable()->after('alt_text');
            $table->string('cta_url')->nullable()->after('cta_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn(['headline', 'subheading', 'cta_text', 'cta_url']);
        });
    }
};
