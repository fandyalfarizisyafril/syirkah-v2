<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Product;

return [
    'categories' => ['model' => Category::class, 'label' => 'Kategori'],
    'brands' => ['model' => Brand::class, 'label' => 'Brand'],
    'products' => ['model' => Product::class, 'label' => 'Produk'],
    'industries' => ['model' => Industry::class, 'label' => 'Industri'],
];
