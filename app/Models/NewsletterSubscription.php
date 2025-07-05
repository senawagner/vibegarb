<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'is_active',
        'subscribed_at',
        'unsubscribed_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
