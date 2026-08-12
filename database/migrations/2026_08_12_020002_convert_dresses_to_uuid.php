<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarded, Postgres-only PK conversion for `dresses`, modeled on the
     * existing precedent in 2026_07_26_105350_alter_users_and_admins_to_uuid.php.
     * No-op on production (dresses.id is already uuid there — confirmed via
     * live introspection) and no-op on SQLite (tests). Only converts a
     * genuinely fresh Postgres install that still has the original bigint PK.
     */
    public function up(): void
    {
        if (! $this->usingPostgres()) {
            return;
        }

        if ($this->isColumnUuid('dresses', 'id')) {
            return;
        }

        Schema::table('dresses', function (Blueprint $table) {
            $table->uuid('new_uuid')->nullable()->after('id');
        });

        DB::table('dresses')->whereNull('new_uuid')->update([
            'new_uuid' => DB::raw('gen_random_uuid()'),
        ]);

        Schema::table('dresses', function (Blueprint $table) {
            $table->uuid('new_uuid')->nullable(false)->change();
            $table->dropColumn('id');
            $table->renameColumn('new_uuid', 'id');
            $table->primary('id');
        });
    }

    public function down(): void
    {
        // Reverting a UUID PK to integer is destructive; intentionally left empty.
    }

    private function isColumnUuid(string $table, string $column): bool
    {
        $type = DB::selectOne('
            SELECT data_type FROM information_schema.columns
            WHERE table_name = ? AND column_name = ?
        ', [$table, $column]);

        return $type && $type->data_type === 'uuid';
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
