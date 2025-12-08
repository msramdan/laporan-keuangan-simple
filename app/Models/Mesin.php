<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesin extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'keterangan',
    ];

    public function transaksiMesins()
    {
        return $this->hasMany(TransaksiMesin::class);
    }
}
