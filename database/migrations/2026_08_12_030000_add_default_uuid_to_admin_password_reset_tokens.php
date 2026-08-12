<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `admin_password_reset_tokens.id` is a uuid NOT NULL column with no
     * default. Laravel's password broker inserts into this table via raw
     * DB::table() queries (Illuminate\Auth\Passwords\DatabaseTokenRepository),
     * not an Eloquent model, so there's no app-layer hook to generate the
     * UUID — every admin "forgot password" request would fail on a NOT NULL
     * violation. Give the column a database-level default instead.
     * No-op on SQLite (no admin password-reset table is created there; the
     * customer-facing table this app actually tests against has no id column
     * at all — see 0001_01_01_000000_create_users_table.php).
     */
    public function up(): void
    {
        if (! $this->usingPostgres()) {
            return;
        }

        if (! Schema::hasTable('admin_password_reset_tokens')) {
            return;
        }

        DB::statement('ALTER TABLE admin_password_reset_tokens ALTER COLUMN id SET DEFAULT gen_random_uuid()');
    }

    public function down(): void
    {
        if (! $this->usingPostgres() || ! Schema::hasTable('admin_password_reset_tokens')) {
            return;
        }

        DB::statement('ALTER TABLE admin_password_reset_tokens ALTER COLUMN id DROP DEFAULT');
    }

    private function usingPostgres(): bool
    {
        try {
            return DB::getDriverName() === 'pgsql';
        } catch (\Throwable $e) {
            return false;
        }
    }
};
