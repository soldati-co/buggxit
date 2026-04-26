<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'title', 'image_path', 'alt_text', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Helper to get full image URL
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}