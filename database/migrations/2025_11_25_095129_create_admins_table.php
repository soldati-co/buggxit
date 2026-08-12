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
        Schema::create('admins', function (Blueprint $table) {
            // Use string primary key for SQLite (tests) to allow UUIDs, integer id otherwise
            // (matches create_users_table; see alter_users_and_admins_to_uuid for the Postgres-side conversion).
            if (DB::getDriverName() === 'sqlite') {
                $table->string('id')->primary();
            } else {
                $table->id();
            }
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};