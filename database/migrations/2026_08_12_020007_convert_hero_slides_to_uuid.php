<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarded, Postgres-only PK conversion for `hero_slides`, matching
     * App\Models\HeroSlide's UUID boot hook. No-op on production (already
     * uuid there) and no-op on SQLite (tests).
     */
    public function up(): void
    {
        if (! Schema::hasColumn('hero_slides', 'deleted_at')) {
            Schema::table('hero_slides', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (! $this->usingPostgres()) {
            return;
        }

        if ($this->isColumnUuid('hero_slides', 'id')) {
            return;
        }

        Schema::table('hero_slides', function (Blueprint $table) {
            $table->uuid('new_uuid')->nullable()->after('id');
        });

        DB::table('hero_slides')->whereNull('new_uuid')->update([
            'new_uuid' => DB::raw('gen_random_uuid()'),
        ]);

        Schema::table('hero_slides', function (Blueprint $table) {
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
