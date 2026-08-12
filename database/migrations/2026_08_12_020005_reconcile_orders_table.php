<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarded catch-up for `orders`: adds the `discount_amount` column that
     * exists live but was never migrated, and converts the PK plus the
     * user_id/shipping_address_id/billing_address_id FKs to uuid to match
     * the already-uuid users/addresses tables. No-op on production (already
     * correct); fresh Postgres installs get the matching shape. No-op on
     * SQLite (tests). Must run after the addresses uuid conversion.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'discount_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('shipping_cost');
            });
        }

        if (! $this->usingPostgres()) {
            return;
        }

        if ($this->isColumnUuid('orders', 'id')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            if (Schema::hasColumn('orders', 'shipping_address_id')) {
                $table->dropForeign(['shipping_address_id']);
            }
            if (Schema::hasColumn('orders', 'billing_address_id')) {
                $table->dropForeign(['billing_address_id']);
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('new_id')->nullable()->after('id');
            $table->uuid('new_user_id')->nullable()->after('user_id');
            if (Schema::hasColumn('orders', 'shipping_address_id')) {
                $table->uuid('new_shipping_address_id')->nullable()->after('shipping_address_id');
            }
            if (Schema::hasColumn('orders', 'billing_address_id')) {
                $table->uuid('new_billing_address_id')->nullable()->after('billing_address_id');
            }
        });

        DB::table('orders')->whereNull('new_id')->update(['new_id' => DB::raw('gen_random_uuid()')]);
        DB::statement('UPDATE orders SET new_user_id = user_id::text::uuid WHERE user_id IS NOT NULL');
        if (Schema::hasColumn('orders', 'shipping_address_id')) {
            DB::statement('UPDATE orders SET new_shipping_address_id = shipping_address_id::text::uuid WHERE shipping_address_id IS NOT NULL');
        }
        if (Schema::hasColumn('orders', 'billing_address_id')) {
            DB::statement('UPDATE orders SET new_billing_address_id = billing_address_id::text::uuid WHERE billing_address_id IS NOT NULL');
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('new_id')->nullable(false)->change();
            $table->dropColumn('id');
            $table->dropColumn('user_id');
            $table->renameColumn('new_id', 'id');
            $table->renameColumn('new_user_id', 'user_id');
            $table->primary('id');

            if (Schema::hasColumn('orders', 'shipping_address_id')) {
                $table->dropColumn('shipping_address_id');
                $table->renameColumn('new_shipping_address_id', 'shipping_address_id');
            }
            if (Schema::hasColumn('orders', 'billing_address_id')) {
                $table->dropColumn('billing_address_id');
                $table->renameColumn('new_billing_address_id', 'billing_address_id');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            if (Schema::hasColumn('orders', 'shipping_address_id')) {
                $table->foreign('shipping_address_id')->references('id')->on('addresses')->nullOnDelete();
            }
            if (Schema::hasColumn('orders', 'billing_address_id')) {
                $table->foreign('billing_address_id')->references('id')->on('addresses')->nullOnDelete();
            }
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
