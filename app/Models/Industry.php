<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Industry extends CatalogEntry
{
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
