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
        Schema::create('transaksi_mesins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('mesin_id')->constrained('mesins')->cascadeOnDelete();
            $table->date('tanggal_transaksi');
            $table->string('nama_produk');
            $table->decimal('harga_pabrik', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->decimal('total_harga_pabrik', 15, 2)->default(0);
            $table->decimal('total_harga_jual', 15, 2)->default(0);
            $table->boolean('status_lunas')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_mesins');
    }
};
