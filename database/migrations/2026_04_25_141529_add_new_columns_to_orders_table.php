<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1) Order number – unique identifier
            //    Set as nullable temporarily if there are existing rows;
            //    later you can make it non-nullable and fill data.
            $table->string('order_number')->unique()->nullable()->after('id');

            // 2) Address foreign keys (addresses table must exist – it does)
            $table->foreignId('shipping_address_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('addresses')
                  ->nullOnDelete();

            $table->foreignId('billing_address_id')
                  ->nullable()
                  ->after('shipping_address_id')
                  ->constrained('addresses')
                  ->nullOnDelete();

            // 3) Financial columns
            $table->decimal('subtotal', 10, 2)->after('billing_address_id');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('subtotal');

            // 4) Payment columns
            $table->string('payment_method')->nullable()->after('status');
            $table->string('payment_status')->default('pending')->after('payment_method');

            // 5) Notes
            $table->text('notes')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_address_id']);
            $table->dropForeign(['billing_address_id']);
            $table->dropColumn([
                'order_number',
                'shipping_address_id',
                'billing_address_id',
                'subtotal',
                'shipping_cost',
                'payment_method',
                'payment_status',
                'notes',
            ]);
        });
    }
};