<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'weight',
        'rejected_weight',
        'price',
        'debt_amount',
        'is_printable',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'rejected_weight' => 'decimal:2',
        'price' => 'decimal:2',
        'debt_amount' => 'decimal:2',
        'is_printable' => 'boolean',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function saleItem()
    {
        return $this->hasOne(SaleItem::class);
    }
}
