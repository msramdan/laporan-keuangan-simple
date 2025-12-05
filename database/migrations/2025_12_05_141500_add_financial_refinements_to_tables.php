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
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('unit_price', 15, 2)->after('weight')->default(0);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total_receivable', 15, 2)->after('grand_total')->default(0);
            $table->decimal('total_paid', 15, 2)->after('total_receivable')->default(0);
            $table->boolean('is_paid')->after('total_paid')->default(false);
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('receivable_amount', 15, 2)->after('selling_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['total_receivable', 'total_paid', 'is_paid']);
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn('receivable_amount');
        });
    }
};
