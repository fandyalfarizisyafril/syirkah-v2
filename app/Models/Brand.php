<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends CatalogEntry
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
