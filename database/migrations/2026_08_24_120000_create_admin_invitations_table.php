<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            // sha256 hash of the plaintext token emailed to the invitee -- never
            // store the plaintext itself, matching Laravel's own password-reset
            // token repository pattern.
            $table->string('token');
            // Not a foreign key: admins.id is a uuid on Postgres but a plain
            // string on SQLite (tests), and this is display/audit-trail data
            // only -- nothing depends on referential integrity here.
            $table->string('invited_by_admin_id')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_invitations');
    }
};
