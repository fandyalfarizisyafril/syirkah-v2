<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return ['name' => fake()->words(3, true), 'slug' => fake()->unique()->slug(), 'category_id' => Category::factory(), 'brand_id' => Brand::factory(), 'short_description' => fake()->sentence(), 'status' => 'published', 'specifications' => [['label' => 'Power', 'value' => '15 kW']]];
    }
}
