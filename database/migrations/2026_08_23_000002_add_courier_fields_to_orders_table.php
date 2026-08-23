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
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'courier_method')) {
                $table->string('courier_method')->nullable()->after('shipping_cost');
            }

            if (! Schema::hasColumn('orders', 'pep_point')) {
                $table->json('pep_point')->nullable()->after('courier_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier_method', 'pep_point']);
        });
    }
};
