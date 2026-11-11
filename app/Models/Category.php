<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'name',
        'slug',
        'audience',
        'group_type',
        'image',
        'tagline',
        'description',
        'sort_order',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Products belonging to this category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope categories to a specific audience (men or kids).
     */
    public function scopeForAudience(Builder $query, string $audience): Builder
    {
        return $query->where('audience', $audience)->orderBy('sort_order');
    }
}
