<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'factory_id',
        'tanggal_transaksi',
        'grand_total',
        'status_lunas',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'grand_total' => 'decimal:2',
        'status_lunas' => 'boolean',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiPembelianDetail::class);
    }

    /**
     * Recalculate grand_total from details
     */
    public function recalculateTotal()
    {
        $this->grand_total = $this->details()->sum('total');
        $this->save();
    }

    /**
     * Update status_lunas based on all details
     */
    public function updateStatusLunas()
    {
        $allPaid = $this->details()->where('status_lunas', false)->count() === 0;
        $this->status_lunas = $allPaid;
        $this->save();
    }
}
