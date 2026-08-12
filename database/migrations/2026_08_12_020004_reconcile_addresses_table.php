<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarded catch-up for `addresses`: adds the `address_type` column that
     * exists live but was never migrated, and converts the PK (and the
     * `user_id` FK that points at the already-uuid `users` table) to uuid.
     * No-op on production (already correct); fresh Postgres installs get the
     * matching shape. No-op on SQLite (tests).
     */
    public function up(): void
    {
        if (! Schema::hasColumn('addresses', 'address_type')) {
            Schema::table('addresses', function (Blueprint $table) {
                $table->string('address_type')->default('both')->after('user_id');
            });
        }

        if (! Schema::hasColumn('addresses', 'deleted_at')) {
            Schema::table('addresses', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (! $this->usingPostgres()) {
            return;
        }

        if ($this->isColumnUuid('addresses', 'id')) {
            return;
        }

        // Drop the user_id FK before retyping either side.
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->uuid('new_id')->nullable()->after('id');
            $table->uuid('new_user_id')->nullable()->after('user_id');
        });

        DB::table('addresses')->whereNull('new_id')->update(['new_id' => DB::raw('gen_random_uuid()')]);
        DB::statement('UPDATE addresses SET new_user_id = user_id::text::uuid WHERE user_id IS NOT NULL');

        Schema::table('addresses', function (Blueprint $table) {
            $table->uuid('new_id')->nullable(false)->change();
            $table->dropColumn('id');
            $table->dropColumn('user_id');
            $table->renameColumn('new_id', 'id');
            $table->renameColumn('new_user_id', 'user_id');
            $table->primary('id');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Additive/PK-type changes; reverting is destructive. Intentionally left empty.
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
