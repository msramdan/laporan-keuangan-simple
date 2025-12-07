<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMesin extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'mesin_id',
        'tanggal_transaksi',
        'nama_produk',
        'banyak_tsg',
        'banyak_tsg_tertolak',
        'harga_pabrik',
        'harga_jual',
        'total_harga_pabrik',
        'total_harga_jual',
        'status_lunas',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'banyak_tsg' => 'decimal:2',
        'banyak_tsg_tertolak' => 'decimal:2',
        'harga_pabrik' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'total_harga_pabrik' => 'decimal:2',
        'total_harga_jual' => 'decimal:2',
        'status_lunas' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function mesin()
    {
        return $this->belongsTo(Mesin::class);
    }
}
