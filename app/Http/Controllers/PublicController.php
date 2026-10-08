<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Industry;
use App\Models\HeroSlide;
use App\Models\Product;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home', [
            'focusSlides' => HeroSlide::slides(),
            'categories' => Category::published()->ordered()->get(), 'brands' => Brand::published()->ordered()->get(),
            'industries' => Industry::published()->ordered()->limit(4)->get(),
            'products' => Product::published()->with(['brand', 'category'])->where('featured', true)->ordered()->limit(4)->get(),
        ]);
    }

    public function products(Request $request, ?string $category = null)
    {
        $filters = $request->validate(['q' => 'nullable|string|max:150', 'category' => 'nullable|integer', 'brand' => 'nullable|integer']);
        $current = $category ? Category::published()->where('slug', $category)->firstOrFail() : null;
        $products = Product::published()->with(['brand', 'category'])->search($filters['q'] ?? null)
            ->when($current, fn ($q) => $q->where('category_id', $current->id))
            ->when($filters['category'] ?? null, fn ($q, $id) => $q->where('category_id', $id))
            ->when($filters['brand'] ?? null, fn ($q, $id) => $q->where('brand_id', $id))->ordered()->paginate(12)->withQueryString();

        return view('public.products', [
            'products' => $products, 'current' => $current, 'categories' => Category::published()->ordered()->get(), 'brands' => Brand::published()->ordered()->get(),
            'relatedBrands' => $current ? Brand::published()->whereHas('products', fn ($q) => $q->published()->where('category_id', $current->id))->ordered()->get() : collect(),
            'relatedIndustries' => $current ? Industry::published()->whereHas('products', fn ($q) => $q->published()->where('category_id', $current->id))->ordered()->get() : collect(),
        ]);
    }

    public function product(string $category, string $product)
    {
        $product = Product::published()->with(['category', 'brand', 'industries' => fn ($q) => $q->published()])
            ->where('slug', $product)->whereHas('category', fn ($q) => $q->where('slug', $category))->firstOrFail();

        return view('public.product', ['product' => $product, 'related' => Product::published()->with(['category', 'brand'])->where('category_id', $product->category_id)->whereKeyNot($product->id)->limit(3)->get()]);
    }

    public function directory(Request $request, ?string $slug = null)
    {
        $isBrand = $request->routeIs('brands.*');
        $model = $isBrand ? Brand::class : Industry::class;
        $entry = $slug ? $model::published()->where('slug', $slug)->firstOrFail() : null;

        return view('public.directory', ['isBrand' => $isBrand, 'entry' => $entry, 'entries' => $entry ? collect() : $model::published()->ordered()->get(), 'products' => $entry ? $entry->products()->published()->with(['category', 'brand'])->ordered()->paginate(12) : null, 'relatedCategories' => $entry ? Category::published()->whereIn('id', $entry->products()->published()->pluck('category_id'))->ordered()->get() : collect()]);
    }

    public function contact(Request $request)
    {
        $request->validate(['product' => 'nullable|integer']);

        return view('public.contact', ['products' => Product::published()->ordered()->get(['id', 'name'])]);
    }

    public function search(Request $request)
    {
        $filters = $request->validate(['q' => 'nullable|string|max:150']);
        $term = $filters['q'] ?? '';
        $results = [];
        foreach (['Kategori' => Category::class, 'Brand' => Brand::class, 'Industri' => Industry::class] as $label => $model) {
            $results[$label] = $model::published()->where('name', 'like', '%'.$term.'%')->ordered()->limit(12)->get();
        }

        return view('public.search', ['term' => $term, 'results' => $results, 'products' => Product::published()->with(['brand', 'category'])->search($term)->ordered()->paginate(12)->withQueryString()]);
    }

    public function sitemap()
    {
        $urls = [url('/'), url('/tentang-kami'), url('/produk'), url('/brand'), url('/industri'), url('/kontak'), url('/privasi')];
        foreach (['products.category' => Category::class, 'brands.show' => Brand::class, 'industries.show' => Industry::class] as $route => $model) {
            foreach ($model::published()->get() as $entry) {
                $urls[] = route($route, $entry->slug);
            }
        }
        foreach (Product::published()->with('category')->get() as $product) {
            $urls[] = $product->url;
        }

        return response()->view('public.sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }
}
