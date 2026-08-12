<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            // Use string primary key for SQLite (tests) to allow UUIDs, integer id otherwise
            // (see 2026_08_12_020004_reconcile_addresses_table for the Postgres-side conversion).
            if (DB::getDriverName() === 'sqlite') {
                $table->string('id')->primary();
            } else {
                $table->id();
            }
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code');
            $table->string('country')->default('South Africa');
            $table->string('phone')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
