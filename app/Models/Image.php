<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // still needed? No, we'll use route

class Image extends Model
{
    protected $fillable = [
        'image_data',   // binary / base64-encoded webp
        'image_mime',
        'imageable_id',
        'imageable_type',
        'sort_order',
        'collection',
    ];

    protected $hidden = [
        'image_data',   // hide raw binary from JSON
    ];

    /**
     * Get the parent imageable model.
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * Get the URL to serve this image via the database.
     */
    public function getUrlAttribute(): string
    {
        return route('image.show', $this->id);
    }

    public function getImageApiUrlAttribute(): ?string
    {
        return $this->image ? $this->image->url : null;
        // or directly: route('image.show', $this->image->id) but using model's accessor is cleaner
    }
}