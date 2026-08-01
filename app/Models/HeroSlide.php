<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string|null $title
 * @property string|null $headline
 * @property string|null $subheading
 * @property string $image_path
 * @property string|null $alt_text
 * @property string|null $cta_text
 * @property string|null $cta_url
 * @property int $sort_order
 * @property bool $is_active
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $image_api_url
 * @property-read \App\Models\Image|null $image
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereCtaText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereCtaUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereHeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereSubheading($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlide whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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