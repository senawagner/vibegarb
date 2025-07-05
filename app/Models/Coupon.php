<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Relacionamentos
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Métodos de validação
    public function isValid($orderTotal = 0)
    {
        // Verifica se está ativo
        if (!$this->is_active) {
            return false;
        }

        // Verifica datas
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        // Verifica limite de uso
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        // Verifica valor mínimo
        if ($this->minimum_amount && $orderTotal < $this->minimum_amount) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($orderTotal)
    {
        if (!$this->isValid($orderTotal)) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($orderTotal * $this->value) / 100;
        }

        return min($this->value, $orderTotal);
    }
}
