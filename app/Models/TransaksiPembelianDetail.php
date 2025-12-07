<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembelianDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_pembelian_id',
        'paket_id',
        'qty',
        'harga_per_unit',
        'total',
        'total_bayar',
        'status_lunas',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_per_unit' => 'decimal:2',
        'total' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'status_lunas' => 'boolean',
    ];

    protected static function booted()
    {
        // Auto-calculate total when saving
        static::saving(function ($detail) {
            $detail->total = $detail->qty * $detail->harga_per_unit;
            $detail->status_lunas = $detail->total_bayar >= $detail->total;
        });

        // Update parent transaction after save
        static::saved(function ($detail) {
            $detail->transaksiPembelian->recalculateTotal();
            $detail->transaksiPembelian->updateStatusLunas();
        });

        // Update parent transaction after delete
        static::deleted(function ($detail) {
            $detail->transaksiPembelian->recalculateTotal();
            $detail->transaksiPembelian->updateStatusLunas();
        });
    }

    public function transaksiPembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    /**
     * Get hutang (remaining debt)
     */
    public function getHutangAttribute()
    {
        return max(0, $this->total - $this->total_bayar);
    }
}
