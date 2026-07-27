<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Dress extends Model
{
    use SoftDeletes;

    // ---------- UUID Primary Key ----------
    public $incrementing = false;
    protected $keyType = 'string';

    // ---------- Standard Color Palette ----------
    public const STANDARD_COLORS = [
        'red'    => 'red',
        'blue'   => 'blue',
        'green'  => 'green',
        'yellow' => 'yellow',
        'purple' => 'purple',
        'pink'   => 'pink',
        'orange' => 'orange',
        'black'  => 'black',
        'white'  => 'white',
        'brown'  => 'brown',
        'gray'   => 'gray',
        'gold'   => 'gold',
        'silver' => 'silver',
        'multi'  => 'multi',
    ];

    // ---------- Fillable & Casts ----------
    protected $fillable = [
        'slug',
        'name',
        'sku',
        'description',
        'price',
        'compare_at_price',
        'stock_quantity',
        'low_stock_threshold',
        'turnaround_time',
        'expected_delivery',
        'status',
        'is_featured',
        'sizes',
        'colors',
        // 'main_image_url' and 'gallery_images' are obsolete now,
        // but you may keep them temporarily until you drop the columns.
        'is_taxable',
        'requires_shipping',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'sizes'          => 'array',
        'colors'         => 'array',
        'is_featured'       => 'boolean',
        'is_taxable'        => 'boolean',
        'requires_shipping' => 'boolean',
        'price'            => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'stock_quantity'     => 'integer',
        'low_stock_threshold' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ---------- Boot ----------
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // ---------- Image Relationships ----------
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function mainImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('collection', 'main');
    }

    public function galleryImages(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')
            ->where('collection', 'gallery')
            ->orderBy('sort_order');
    }

    // ---------- Image URL Helpers (use these in your views) ----------
    /**
     * API URL for the main image (null if none).
     */
    public function getMainImageUrlAttribute(): ?string
    {
        return $this->mainImage ? route('api.image.show', $this->mainImage->id) : null;
    }

    /**
     * List of API URLs for gallery images.
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        return $this->galleryImages->map(fn($img) => route('api.image.show', $img->id))->toArray();
    }

    // ---------- Other Relationships ----------
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_dress')
                    ->withTimestamps()
                    ->orderBy('sort_order');
    }

    public function getCategoryNamesAttribute()
    {
        return $this->categories->pluck('name')->join(', ');
    }

    // ---------- Scopes ----------
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= low_stock_threshold');
    }

    // ---------- Custom Accessors & Helpers ----------
    public function getDisplaySkuAttribute()
    {
        return $this->sku;
    }

    public function getAvailableSizesAttribute()
    {
        $allSizes = range(32, 42);
        $selectedSizes = $this->sizes ?? [];
        return array_values(array_filter($selectedSizes, function ($size) use ($allSizes) {
            return in_array($size, $allSizes, true);
        }));
    }

    public function getDisplayColorsAttribute()
    {
        $colors = $this->colors ?? [];
        $standard = self::STANDARD_COLORS;

        return array_map(function ($color) use ($standard) {
            if (array_key_exists($color, $standard)) {
                return [
                    'name'      => ucfirst($color),
                    'hex'       => $standard[$color],
                    'is_custom' => false,
                ];
            }
            $hex = str_starts_with($color, '#') ? $color : '#' . $color;
            return [
                'name'      => 'Custom',
                'hex'       => $hex,
                'is_custom' => true,
            ];
        }, $colors);
    }

    public function getColorHexesAttribute()
    {
        return array_column($this->display_colors, 'hex');
    }
}