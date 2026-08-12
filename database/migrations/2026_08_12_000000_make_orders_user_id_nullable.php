<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Checkout (CheckoutController / CheckoutApiController) supports a guest
     * flow ('email' => 'required_if:guest,true') and neither the web nor the
     * api checkout routes require authentication, so `auth()->id()` can be
     * null when an order is created. The original orders table defined
     * user_id as a NOT NULL foreign key, which made every guest checkout
     * attempt fail with a database constraint violation. Make it nullable
     * so guest orders can actually be persisted.
     *
     * On Postgres, Blueprint::change() re-emits the column's full type
     * definition (including an implicit `USING` cast) even though only the
     * nullability is changing, and Postgres refuses to auto-cast bigint ->
     * bigint in that form. Drop just the NOT NULL constraint directly there;
     * other drivers (sqlite in tests) keep using the Blueprint change().
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE orders ALTER COLUMN user_id DROP NOT NULL');
        } else {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE orders ALTER COLUMN user_id SET NOT NULL');
        } else {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }
    }
};
