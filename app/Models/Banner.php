<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'link',
        'order',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the full URL for the banner's image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    // Scope para banners ativos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope para ordenação
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
