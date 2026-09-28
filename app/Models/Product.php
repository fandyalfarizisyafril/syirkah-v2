<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends CatalogEntry
{
    protected function casts(): array
    {
        return ['benefits' => 'array', 'applications' => 'array', 'specifications' => 'array', 'gallery' => 'array', 'featured' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(Industry::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return parent::scopePublished($query)->whereHas('category', fn ($q) => $q->published())->whereHas('brand', fn ($q) => $q->published());
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($query) use ($term) {
            $like = '%'.$term.'%';
            $query->where('name', 'like', $like)->orWhere('short_description', 'like', $like)
                ->orWhere('model_or_series', 'like', $like)
                ->orWhereHas('brand', fn ($q) => $q->where('name', 'like', $like))
                ->orWhereHas('category', fn ($q) => $q->where('name', 'like', $like));
        });
    }

    public function getUrlAttribute(): string
    {
        return route('products.show', [$this->category->slug, $this->slug]);
    }
}
