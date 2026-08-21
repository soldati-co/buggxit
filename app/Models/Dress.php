<?php

namespace App\Models;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|Image[] $images
 * @property-read Image|null $mainImage
 * @property-read Image|null $cardImage
 * @property-read Image|null $cardImageNewArrivals
 * @property-read Image|null $detailImage
 * @property-read \Illuminate\Database\Eloquent\Collection|Image[] $galleryImages
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $categories
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string|null $custom_sku
 * @property string|null $description
 * @property numeric $price
 * @property numeric|null $compare_at_price
 * @property int $stock_quantity
 * @property int|null $low_stock_threshold
 * @property array<array-key, mixed>|null $sizes
 * @property array<array-key, mixed>|null $colors
 * @property string|null $main_image_url
 * @property string|null $gallery_images
 * @property string $status
 * @property bool $is_featured
 * @property bool $is_taxable
 * @property bool $requires_shipping
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $turnaround_time
 * @property string|null $expected_delivery
 * @property-read int|null $categories_count
 * @property-read int|null $gallery_images_count
 * @property-read mixed $available_sizes
 * @property-read mixed $category_names
 * @property-read mixed $color_hexes
 * @property-read mixed $display_colors
 * @property-read mixed $display_sku
 * @property-read array $gallery_image_urls
 * @property-read string $card_image_url
 * @property-read bool $has_card_crop
 * @property-read string $card_image_new_arrivals_url
 * @property-read bool $has_card_crop_new_arrivals
 * @property-read string $detail_image_url
 * @property-read bool $has_detail_crop
 * @property-read bool $has_main_image
 * @property-read int|null $images_count
 * @method static Builder<static>|Dress active()
 * @method static Builder<static>|Dress featured()
 * @method static Builder<static>|Dress inStock()
 * @method static Builder<static>|Dress lowStock()
 * @method static Builder<static>|Dress newModelQuery()
 * @method static Builder<static>|Dress newQuery()
 * @method static Builder<static>|Dress onlyTrashed()
 * @method static Builder<static>|Dress query()
 * @method static Builder<static>|Dress whereColors($value)
 * @method static Builder<static>|Dress whereCompareAtPrice($value)
 * @method static Builder<static>|Dress whereCreatedAt($value)
 * @method static Builder<static>|Dress whereCustomSku($value)
 * @method static Builder<static>|Dress whereDeletedAt($value)
 * @method static Builder<static>|Dress whereDescription($value)
 * @method static Builder<static>|Dress whereExpectedDelivery($value)
 * @method static Builder<static>|Dress whereGalleryImages($value)
 * @method static Builder<static>|Dress whereId($value)
 * @method static Builder<static>|Dress whereIsFeatured($value)
 * @method static Builder<static>|Dress whereIsTaxable($value)
 * @method static Builder<static>|Dress whereLowStockThreshold($value)
 * @method static Builder<static>|Dress whereMainImageUrl($value)
 * @method static Builder<static>|Dress whereMetaDescription($value)
 * @method static Builder<static>|Dress whereMetaTitle($value)
 * @method static Builder<static>|Dress whereName($value)
 * @method static Builder<static>|Dress wherePrice($value)
 * @method static Builder<static>|Dress whereRequiresShipping($value)
 * @method static Builder<static>|Dress whereSizes($value)
 * @method static Builder<static>|Dress whereSlug($value)
 * @method static Builder<static>|Dress whereStatus($value)
 * @method static Builder<static>|Dress whereStockQuantity($value)
 * @method static Builder<static>|Dress whereTurnaroundTime($value)
 * @method static Builder<static>|Dress whereUpdatedAt($value)
 * @method static Builder<static>|Dress withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Dress withoutTrashed()
 * @mixin \Eloquent
 */
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Dress extends Model
{
    use HasFactory, SoftDeletes;

    // ---------- UUID Primary Key ----------
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Resolve a bound value for route model binding by slug first, falling
     * back to the UUID primary key. The storefront links to dresses using
     * `dress.slug` (see DressResource / ProductApiController), but Laravel's
     * default implicit binding only looks up by `id` since getRouteKeyName()
     * isn't overridden — that made every slug-based product URL 404.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field !== null) {
            return parent::resolveRouteBinding($value, $field);
        }

        return $this->where('slug', $value)->first()
            ?? $this->where('id', $value)->first();
    }

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
        return $this->morphOne(Image::class, 'imageable')->where('collection', 'main')->latest('id');
    }

    /**
     * The admin's deliberately-cropped image for the main card grid
     * (products listing + homepage featured section — both render an
     * identical box shape) — distinct from mainImage() so the product
     * detail page can keep showing the true, uncropped original.
     */
    public function cardImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('collection', 'card')->latest('id');
    }

    /**
     * A second, independent crop for the homepage "New Arrivals" section,
     * which renders a different box shape than the main card grid — one
     * crop can't serve both contexts well.
     */
    public function cardImageNewArrivals(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('collection', 'card_new_arrivals')->latest('id');
    }

    /**
     * A third, independent crop for the product detail page's large hero
     * image box — a true 1:1 square at every viewport (unlike the card
     * boxes, which only approximate one).
     */
    public function detailImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('collection', 'detail')->latest('id');
    }

    public function galleryImages(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')
            ->where('collection', 'gallery')
            ->orderBy('sort_order');
    }

    /**
     * Eager-load main/gallery images with only the columns needed to
     * build URLs (never pulls the multi-MB image_data column), so
     * listing endpoints don't issue a query per dress per image.
     */
    public function scopeWithImageUrls(Builder $query): Builder
    {
        if (! static::imagesTableExists()) {
            return $query;
        }

        return $query->with([
            'mainImage' => fn($q) => $q->select('id', 'imageable_id', 'imageable_type', 'collection')
                ->whereNotNull('image_data')
                ->where('image_data', '!=', ''),
            'cardImage' => fn($q) => $q->select('id', 'imageable_id', 'imageable_type', 'collection')
                ->whereNotNull('image_data')
                ->where('image_data', '!=', ''),
            'cardImageNewArrivals' => fn($q) => $q->select('id', 'imageable_id', 'imageable_type', 'collection')
                ->whereNotNull('image_data')
                ->where('image_data', '!=', ''),
            'detailImage' => fn($q) => $q->select('id', 'imageable_id', 'imageable_type', 'collection')
                ->whereNotNull('image_data')
                ->where('image_data', '!=', ''),
            'galleryImages' => fn($q) => $q->select('id', 'imageable_id', 'imageable_type', 'collection', 'sort_order'),
        ]);
    }

    // ---------- Image URL Helpers (use these in your views) ----------
    protected static ?bool $imagesTableExists = null;

    protected static function imagesTableExists(): bool
    {
        return static::$imagesTableExists ??= Schema::hasTable('images');
    }

    /**
     * API URL for the main image (null if none).
     */
    public function getMainImageUrlAttribute(): string
    {
        if (! static::imagesTableExists()) {
            return asset('logo.webp');
        }

        $mainImageId = $this->relationLoaded('mainImage')
            ? $this->mainImage?->id
            : $this->mainImage()->whereNotNull('image_data')->where('image_data', '!=', '')->value('id');

        if ($mainImageId) {
            return route('api.image.show', $mainImageId);
        }

        return asset('logo.webp');
    }

    /**
     * Whether a real main image exists — main_image_url always falls back
     * to a placeholder logo, so this is needed anywhere that distinction
     * matters (e.g. deciding whether there's anything to crop).
     */
    public function getHasMainImageAttribute(): bool
    {
        if (! static::imagesTableExists()) {
            return false;
        }

        if ($this->relationLoaded('mainImage')) {
            return (bool) $this->mainImage;
        }

        return $this->mainImage()->whereNotNull('image_data')->where('image_data', '!=', '')->exists();
    }

    /**
     * API URL for the card-crop image. Falls back to the full main image
     * (still object-contain-safe) when no deliberate crop exists yet — a
     * dress is never left with a broken image just because it hasn't been
     * cropped for cards.
     */
    public function getCardImageUrlAttribute(): string
    {
        if (! static::imagesTableExists()) {
            return $this->main_image_url;
        }

        $cardImageId = $this->relationLoaded('cardImage')
            ? $this->cardImage?->id
            : $this->cardImage()->whereNotNull('image_data')->where('image_data', '!=', '')->value('id');

        if ($cardImageId) {
            return route('api.image.show', $cardImageId);
        }

        return $this->main_image_url;
    }

    /**
     * True only when a dedicated 'card' collection image exists — templates
     * use this to pick object-cover (safe, deliberate crop) vs
     * object-contain (unmodified original, may letterbox).
     */
    public function getHasCardCropAttribute(): bool
    {
        if (! static::imagesTableExists()) {
            return false;
        }

        if ($this->relationLoaded('cardImage')) {
            return (bool) $this->cardImage;
        }

        return $this->cardImage()->whereNotNull('image_data')->where('image_data', '!=', '')->exists();
    }

    /**
     * API URL for the New Arrivals crop. Cascades: dedicated New Arrivals
     * crop -> the main card-grid crop (still a deliberate composition,
     * better than nothing) -> the full main image (object-contain-safe).
     */
    public function getCardImageNewArrivalsUrlAttribute(): string
    {
        if (! static::imagesTableExists()) {
            return $this->main_image_url;
        }

        $imageId = $this->relationLoaded('cardImageNewArrivals')
            ? $this->cardImageNewArrivals?->id
            : $this->cardImageNewArrivals()->whereNotNull('image_data')->where('image_data', '!=', '')->value('id');

        if ($imageId) {
            return route('api.image.show', $imageId);
        }

        return $this->card_image_url;
    }

    /**
     * True when the New Arrivals card is showing some deliberate crop
     * (its own dedicated crop, or falling back to the card-grid crop) —
     * either way it's safe to object-cover rather than object-contain.
     */
    public function getHasCardCropNewArrivalsAttribute(): bool
    {
        if (! static::imagesTableExists()) {
            return false;
        }

        $hasDedicated = $this->relationLoaded('cardImageNewArrivals')
            ? (bool) $this->cardImageNewArrivals
            : $this->cardImageNewArrivals()->whereNotNull('image_data')->where('image_data', '!=', '')->exists();

        return $hasDedicated || $this->has_card_crop;
    }

    /**
     * API URL for the product detail page's hero image. Falls back to the
     * full main image (object-contain-safe) when uncropped — unlike the
     * card crops, there's no intermediate fallback to try since this is
     * the only large-hero display context.
     */
    public function getDetailImageUrlAttribute(): string
    {
        if (! static::imagesTableExists()) {
            return $this->main_image_url;
        }

        $imageId = $this->relationLoaded('detailImage')
            ? $this->detailImage?->id
            : $this->detailImage()->whereNotNull('image_data')->where('image_data', '!=', '')->value('id');

        if ($imageId) {
            return route('api.image.show', $imageId);
        }

        return $this->main_image_url;
    }

    /**
     * True only when a dedicated 'detail' collection image exists —
     * products/show.blade.php uses this to pick object-cover (safe,
     * deliberate crop) vs object-contain (unmodified original).
     */
    public function getHasDetailCropAttribute(): bool
    {
        if (! static::imagesTableExists()) {
            return false;
        }

        if ($this->relationLoaded('detailImage')) {
            return (bool) $this->detailImage;
        }

        return $this->detailImage()->whereNotNull('image_data')->where('image_data', '!=', '')->exists();
    }

    /**
     * List of API URLs for gallery images.
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        if (! static::imagesTableExists()) {
            return [];
        }

        $ids = $this->relationLoaded('galleryImages')
            ? $this->galleryImages->pluck('id')
            : $this->galleryImages()->pluck('id');

        return $ids->map(fn($id) => route('api.image.show', $id))->toArray();
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
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock(Builder $query): Builder
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
            // $selectedSizes come from validated request input (form/JSON), so
            // they may arrive as numeric strings (e.g. "32") while $allSizes is
            // always an array of ints. Cast before the strict comparison so a
            // valid size isn't filtered out just because of a type mismatch.
            return in_array((int) $size, $allSizes, true);
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