<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Dress extends Model
{
    use SoftDeletes;

    // ---------- UUID Primary Key ----------
    public $incrementing = false;
    protected $keyType = 'string';

    // ---------- Standard Color Palette (Hex Mapping) ----------
    public const STANDARD_COLORS = [
        'red'    => '#FF0000',
        'blue'   => '#0000FF',
        'green'  => '#008000',
        'yellow' => '#FFFF00',
        'purple' => '#800080',
        'pink'   => '#FFC0CB',
        'orange' => '#FFA500',
        'black'  => '#000000',
        'white'  => '#FFFFFF',
        'brown'  => '#A52A2A',
        'gray'   => '#808080',
        'gold'   => '#FFD700',
        'silver' => '#C0C0C0',
        'multi'  => '#FF00FF', // fallback
    ];

    // ---------- Fillable & Casts ----------
    protected $fillable = [
        'slug',
        'name',
        'sku_prefix',
        'custom_sku',
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
        'colors',           // JSON array – can contain keys or hex strings
        'main_image_url',
        'gallery_images',
        'is_taxable',
        'requires_shipping',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        // JSON columns
        'sizes'          => 'array',
        'colors'         => 'array',        // stored as simple array of strings
        'gallery_images' => 'array',

        // Booleans
        'is_featured'       => 'boolean',
        'is_taxable'        => 'boolean',
        'requires_shipping' => 'boolean',

        // Decimals
        'price'            => 'decimal:2',
        'compare_at_price' => 'decimal:2',

        // Integers
        'stock_quantity'     => 'integer',
        'low_stock_threshold' => 'integer',

        // Timestamps
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ---------- Boot (UUID generation) ----------
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // ---------- Relationships ----------
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
        return $this->sku_prefix === 'CUSTOM'
            ? $this->custom_sku
            : $this->sku_prefix;
    }

    /**
     * Get available sizes (filtered to the 32-42 range).
     */
    public function getAvailableSizesAttribute()
    {
        $allSizes = range(32, 42);
        $selectedSizes = $this->sizes ?? [];

        return array_values(array_filter($selectedSizes, function ($size) use ($allSizes) {
            return in_array($size, $allSizes, true);
        }));
    }

    /**
     * Get structured color data for display.
     * Each item: ['name' => string, 'hex' => string, 'is_custom' => bool]
     */
    public function getDisplayColorsAttribute()
    {
        $colors = $this->colors ?? [];
        $standard = self::STANDARD_COLORS;

        return array_map(function ($color) use ($standard) {
            // If the value is a known key in our standard palette
            if (array_key_exists($color, $standard)) {
                return [
                    'name'      => ucfirst($color),
                    'hex'       => $standard[$color],
                    'is_custom' => false,
                ];
            }

            // Otherwise, treat it as a hex code (custom color)
            // Ensure it has a # prefix for safety
            $hex = str_starts_with($color, '#') ? $color : '#' . $color;
            return [
                'name'      => 'Custom',
                'hex'       => $hex,
                'is_custom' => true,
            ];
        }, $colors);
    }

    /**
     * Get only the hex values of all colors (useful for swatches).
     */
    public function getColorHexesAttribute()
    {
        return array_column($this->display_colors, 'hex');
    }
}