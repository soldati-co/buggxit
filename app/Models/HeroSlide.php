<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class HeroSlide extends Model
{
    // Use UUID primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'image_path',          // keep for now, will be removed later
        'alt_text',
        'sort_order',
        'is_active',
        'headline',
        'subheading',
        'cta_text',
        'cta_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // ---------- Image Relationship ----------
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('collection', 'hero');
    }

    /**
     * API URL of the slide's image.
     * Use this in your frontend instead of the old `image_url`.
     */
    public function getImageApiUrlAttribute(): ?string
    {
        return $this->image ? route('api.image.show', $this->image->id) : null;
    }

    // ---------- Scopes ----------
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }
}