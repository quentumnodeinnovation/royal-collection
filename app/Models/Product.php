<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'category_id',
        'audience',
        'name',
        'slug',
        'sku',
        'subtitle',
        'fit_badge',
        'fabric_badge',
        'price',
        'mrp',
        'image',
        'color_name',
        'color_hex',
        'sizes',
        'description',
        'fabric_details',
        'is_trending',
        'is_featured',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'sizes' => 'array',
            'is_trending' => 'boolean',
            'is_featured' => 'boolean',
            'price' => 'integer',
            'mrp' => 'integer',
            'stock' => 'integer',
        ];
    }

    /**
     * Category this product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Calculate discount percentage off MRP.
     */
    public function getDiscountPercentAttribute(): int
    {
        if ($this->mrp <= $this->price || $this->mrp === 0) {
            return 0;
        }

        return (int) round((($this->mrp - $this->price) / $this->mrp) * 100);
    }

    /**
     * Scope query by optional audience (men|kids).
     */
    public function scopeAudience(Builder $query, ?string $audience): Builder
    {
        if (in_array($audience, ['men', 'kids'], true)) {
            $query->where('audience', $audience);
        }

        return $query;
    }
}
