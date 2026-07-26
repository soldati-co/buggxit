<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'image_data',
        'image_mime',
        'imageable_id',
        'imageable_type',
        'sort_order',
        'collection',
    ];

    protected $hidden = [
        'image_data',   // hide binary data when model is serialised to JSON
    ];

    /**
     * Get the parent imageable model (dress, hero slide, etc.)
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}