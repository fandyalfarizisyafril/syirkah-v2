<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class CatalogEntry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), 'published');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/'.$this->image) : asset('images/catalog-placeholder.svg');
    }
}
