<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarded catch-up for `order_items`: adds the `attributes` column that
     * exists live but was never migrated (likely intended for per-item
     * size/color selection — not currently written by any controller), and
     * converts the PK plus order_id/dress_id FKs to uuid to match the
     * already-uuid orders/dresses tables. No-op on production; fresh
     * Postgres installs get the matching shape. No-op on SQLite (tests).
     * Must run after the orders uuid conversion.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('order_items', 'attributes')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->json('attributes')->nullable()->after('dress_id');
            });
        }

        if (! $this->usingPostgres()) {
            return;
        }

        if ($this->isColumnUuid('order_items', 'id')) {
            return;
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['dress_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->uuid('new_id')->nullable()->after('id');
            $table->uuid('new_order_id')->nullable()->after('order_id');
            $table->uuid('new_dress_id')->nullable()->after('dress_id');
        });

        DB::table('order_items')->whereNull('new_id')->update(['new_id' => DB::raw('gen_random_uuid()')]);
        DB::statement('UPDATE order_items SET new_order_id = order_id::text::uuid WHERE order_id IS NOT NULL');
        DB::statement('UPDATE order_items SET new_dress_id = dress_id::text::uuid WHERE dress_id IS NOT NULL');

        Schema::table('order_items', function (Blueprint $table) {
            $table->uuid('new_id')->nullable(false)->change();
            $table->uuid('new_order_id')->nullable(false)->change();
            $table->dropColumn('id');
            $table->dropColumn('order_id');
            $table->dropColumn('dress_id');
            $table->renameColumn('new_id', 'id');
            $table->renameColumn('new_order_id', 'order_id');
            $table->renameColumn('new_dress_id', 'dress_id');
            $table->primary('id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('dress_id')->references('id')->on('dresses')->nullOnDelete();
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
