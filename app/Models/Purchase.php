<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'factory_id',
        'date',
        'grand_total',
        'total_paid',
        'total_debt',
        'is_paid',
    ];

    protected $casts = [
        'date' => 'date',
        'grand_total' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'total_debt' => 'decimal:2',
        'is_paid' => 'boolean',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
