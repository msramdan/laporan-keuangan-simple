<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nota_type',
        'reference_id',
        'parameters',
        'created_by',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for purchase notas
     */
    public function scopePurchase($query)
    {
        return $query->where('nota_type', 'purchase');
    }

    /**
     * Scope for machine factory notas
     */
    public function scopeMachineFactory($query)
    {
        return $query->where('nota_type', 'machine_factory');
    }

    /**
     * Scope for machine sales notas
     */
    public function scopeMachineSales($query)
    {
        return $query->where('nota_type', 'machine_sales');
    }
}
