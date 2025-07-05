<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'price',
        'total'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeForGuest($query, $sessionId)
    {
        return $query->whereNull('user_id')->where('session_id', $sessionId);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getFormattedTotalAttribute()
    {
        return 'R$ ' . number_format($this->total, 2, ',', '.');
    }

    public function getCurrentProductPriceAttribute()
    {
        if ($this->productVariant) {
            return $this->productVariant->final_price;
        }
        return $this->product->current_price;
    }

    public function getProductNameAttribute()
    {
        $name = $this->product->name;
        
        if ($this->productVariant) {
            if ($this->productVariant->type === 'size') {
                $name .= " - Tamanho {$this->productVariant->value}";
            } elseif ($this->productVariant->type === 'color') {
                $name .= " - Cor {$this->productVariant->value}";
            }
        }
        
        return $name;
    }

    // Métodos
    public function updateQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->total = $this->price * $quantity;
        $this->save();
    }

    public function incrementQuantity($amount = 1)
    {
        $this->updateQuantity($this->quantity + $amount);
    }

    public function decrementQuantity($amount = 1)
    {
        $newQuantity = max(1, $this->quantity - $amount);
        $this->updateQuantity($newQuantity);
    }

    // Boot method para auto-calcular total
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cartItem) {
            $cartItem->total = $cartItem->price * $cartItem->quantity;
        });

        static::updating(function ($cartItem) {
            if ($cartItem->isDirty(['price', 'quantity'])) {
                $cartItem->total = $cartItem->price * $cartItem->quantity;
            }
        });
    }
}
