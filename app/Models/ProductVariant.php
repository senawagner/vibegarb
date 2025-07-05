<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'type',
        'value',
        'price_adjustment',
        'stock_quantity',
        'sku',
        'is_active'
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relacionamentos
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getFinalPriceAttribute()
    {
        return $this->product->current_price + $this->price_adjustment;
    }

    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->final_price, 2, ',', '.');
    }

    public function getSizeAttribute()
    {
        return $this->type === 'size' ? $this->value : null;
    }

    public function getColorAttribute()
    {
        return $this->type === 'color' ? $this->value : null;
    }

    // Métodos
    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    public function decreaseStock($quantity)
    {
        $this->decrement('stock_quantity', $quantity);
    }
}
