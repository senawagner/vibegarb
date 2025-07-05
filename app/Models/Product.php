<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'weight',
        'dimensions',
        'is_active',
        'is_featured',
        'images',
        'attributes',
        'category_id',
        'quality_line',
        'target_audience',
        'available_colors',
        'available_sizes',
        'cost_price',
        'production_days',
        'supplier_id',
        'featured',
        'active',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'array',
        'attributes' => 'array',
        'cost_price' => 'decimal:2',
        'production_days' => 'integer',
        'featured' => 'boolean',
        'active' => 'boolean',
    ];

    // Relacionamentos
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByQuality($query, $quality)
    {
        return $query->where('quality_line', $quality);
    }

    public function scopeByAudience($query, $audience)
    {
        return $query->where('target_audience', $audience);
    }

    // Accessors
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->current_price, 2, ',', '.');
    }

    public function getPrimaryImageUrlAttribute()
    {
        $primaryImage = $this->productImages()->where('is_primary', true)->first()
            ?? $this->productImages()->first();
        
        return $primaryImage ? $primaryImage->image_url : null;
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function getPrimaryImageAttribute()
    {
        return $this->productImages()->where('is_primary', true)->first()
            ?? $this->productImages()->first();
    }

    public function getQualityDescriptionAttribute()
    {
        $descriptions = [
            'classic' => 'Linha econômica com boa qualidade',
            'quality' => 'Custo-benefício com costura reforçada',
            'prime' => 'Qualidade superior, malha pré-lavada',
            'pima' => 'Premium com algodão Pima de alta qualidade',
            'estonada' => 'Malha com aspecto vintage',
            'dry_sport' => 'Performance para atividades físicas'
        ];

        return $descriptions[$this->quality_line] ?? '';
    }

    public function getAvailableColorsArrayAttribute()
    {
        return $this->available_colors ? explode(',', $this->available_colors) : [];
    }

    public function getAvailableSizesArrayAttribute()
    {
        return $this->available_sizes ? explode(',', $this->available_sizes) : [];
    }

    // Métodos para dropshipping (sem estoque físico)
    public function isAvailable()
    {
        return $this->is_active; // Sempre disponível se ativo (dropshipping)
    }

    public function getVariantPrice($size, $color)
    {
        $variant = $this->variants()
            ->where('size', $size)
            ->where('color', $color)
            ->first();

        return $this->current_price + ($variant->additional_price ?? 0);
    }

    public function hasVariant($size, $color)
    {
        return $this->variants()
            ->where('size', $size)
            ->where('color', $color)
            ->exists();
    }

    public function getDisplayName()
    {
        return $this->name . ' - ' . ucfirst($this->quality_line);
    }

    // Métodos removidos/ajustados para dropshipping
    // public function isInStock() - Não necessário
    // public function decreaseStock() - Não necessário
}
