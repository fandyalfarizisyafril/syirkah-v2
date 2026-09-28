<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            ['Electric Motors & Generators', 'Wolong', 'Electric motors, generators, PM motors, dan drive control untuk kebutuhan penggerak industri.', 'Electric Motor'],
            ['Vibration Technology', 'OLI', 'Internal dan external vibrator, screen vibrator, serta converter untuk kebutuhan proses material.', 'Industrial Vibrator'],
            ['Chemical Metering Pump', 'Qdos', 'Chemical dosing pump untuk kebutuhan penakaran bahan kimia dalam proses industri.', 'Chemical Dosing Pump'],
            ['Hose Pump', 'Bredel', 'Hose pump untuk pemindahan fluida pada proses industri.', 'Hose Pump'],
            ['Industrial Hose', 'Aflex', 'PTFE hose untuk kebutuhan transfer fluida industri.', 'PTFE Hose'],
            ['Air Compressor', 'Tecbell', 'Air compressor dan refrigerated dryer untuk kebutuhan sistem udara bertekanan.', 'Air Compressor'],
            ['Oil Spill Response & Prevention', 'BLU-C', 'Oil boom, absorbent, skimmer, dispersant, dan spill kit untuk penanganan tumpahan minyak.', 'Oil Spill Kit'],
        ];
        foreach ($catalog as $order => [$name, $brandName, $description, $productName]) {
            $category = Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => $description, 'sort_order' => $order, 'status' => 'published']);
            $brand = Brand::firstOrCreate(['slug' => Str::slug($brandName)], ['name' => $brandName, 'focus' => $name, 'description' => $description, 'sort_order' => $order, 'status' => 'published']);
            // Product names from the PRD are drafts until specifications and assets are approved.
            Product::firstOrCreate(['slug' => Str::slug($brandName.' '.$productName)], ['name' => $brandName.' '.$productName, 'category_id' => $category->id, 'brand_id' => $brand->id, 'short_description' => $description, 'status' => 'draft', 'sort_order' => $order]);
        }
        foreach (['Oil & Gas', 'Mining', 'Chemical', 'Manufacturing', 'Marine', 'Construction'] as $order => $name) {
            Industry::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'sort_order' => $order, 'status' => 'draft']);
        }
        Setting::firstOrCreate(['id' => 1], ['data' => Setting::defaults()]);
    }
}
