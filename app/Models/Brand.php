<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends CatalogEntry
{
    protected function casts(): array
    {
        return ['technology_details' => 'array'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
