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
        Schema::table('transaksi_mesins', function (Blueprint $table) {
            $table->decimal('banyak_tsg', 15, 2)->default(0)->after('nama_produk');
            $table->decimal('banyak_tsg_tertolak', 15, 2)->default(0)->after('banyak_tsg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_mesins', function (Blueprint $table) {
            $table->dropColumn(['banyak_tsg', 'banyak_tsg_tertolak']);
        });
    }
};
