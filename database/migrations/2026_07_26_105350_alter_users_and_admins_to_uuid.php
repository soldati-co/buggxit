<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! $this->usingPostgres()) {
            return;
        }

        // Only run UUID conversion on PostgreSQL to avoid breaking SQLite tests
        if (! $this->usingPostgres()) {
            return;
        }

        // Convert users.id to UUID (if not already)
        $this->convertTableToUuid('users');

        // Convert admins.id to UUID (if not already)
        $this->convertTableToUuid('admins');

        // Update sessions.user_id to UUID to match users.id
        $this->updateSessionsForeignKey();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting a UUID to integer is destructive and usually not needed.
        // You can manually add rollback logic if you must, but it's safer to leave empty.
        //
        // Example for users (destructive – data loss):
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropPrimary('users_pkey');
        //     $table->unsignedBigInteger('new_id')->autoIncrement()->first();
        //     DB::table('users')->update(['new_id' => DB::raw('id::bigint')]); // will fail
        // });
    }

    /**
     * Convert the primary key of a table to UUID.
     */
    private function convertTableToUuid(string $table): void
    {
        // Skip if the id column is already a UUID (e.g., admins already converted)
        if (Schema::hasColumn($table, 'id') && $this->isColumnUuid($table, 'id')) {
            return;
        }

        // Add a temporary UUID column
        Schema::table($table, function (Blueprint $table) {
            $table->uuid('new_uuid')->nullable()->after('id');
        });

        // Generate UUIDs for all existing rows
        DB::table($table)->whereNull('new_uuid')->update([
            'new_uuid' => DB::raw('gen_random_uuid()')
        ]);

        // Make the column not nullable, drop old id, rename new_uuid to id, and set as primary
        Schema::table($table, function (Blueprint $table) {
            $table->uuid('new_uuid')->nullable(false)->change();
            $table->dropColumn('id');
            $table->renameColumn('new_uuid', 'id');
            $table->primary('id');
        });
    }

    /**
     * Update the sessions table to reference a UUID user_id.
     */
    private function updateSessionsForeignKey(): void
    {
        // This only runs if the old foreign key exists (from the default sessions migration)
        if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'user_id')) {
            // Remove the old index and foreign key
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropForeign(['user_id']);   // drop foreign if exists
                $table->dropIndex(['user_id']);     // drop implicit index
            });

            // Change user_id column type to UUID
            Schema::table('sessions', function (Blueprint $table) {
                $table->uuid('user_id')->nullable()->change();
            });

            // Re-create the index and foreign key
            Schema::table('sessions', function (Blueprint $table) {
                $table->index('user_id');
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Check if a column is of type UUID.
     */
    private function isColumnUuid(string $table, string $column): bool
    {
        // Only check column types on PostgreSQL
        if (! $this->usingPostgres()) {
            return false;
        }

        $type = DB::selectOne("
            SELECT data_type
            FROM information_schema.columns
            WHERE table_name = ? AND column_name = ?
        ", [$table, $column]);

        return $type && $type->data_type === 'uuid';
    }

    /**
     * Determine if the current connection is PostgreSQL.
     */
    private function usingPostgres(): bool
    {
        try {
            return DB::getDriverName() === 'pgsql';
        } catch (\Throwable $e) {
            return false;
        }
    }
};