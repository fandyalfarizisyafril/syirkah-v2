<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
    private function module(string $module): array
    {
        abort_unless(array_key_exists($module, config('catalog')), 404);

        return config('catalog.'.$module);
    }

    public function index(Request $request, string $module)
    {
        $config = $this->module($module);
        $filters = $request->validate(['q' => 'nullable|string|max:150', 'status' => 'nullable|in:draft,published', 'category' => 'nullable|integer', 'brand' => 'nullable|integer']);
        $query = $config['model']::query()->when($filters['q'] ?? null, fn ($q, $term) => $q->where('name', 'like', '%'.$term.'%'))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status));
        if ($module === 'products') {
            $query->with(['brand', 'category'])->when($filters['category'] ?? null, fn ($q, $id) => $q->where('category_id', $id))
                ->when($filters['brand'] ?? null, fn ($q, $id) => $q->where('brand_id', $id));
        }

        return view('admin.catalog.index', compact('module', 'config') + ['entries' => $query->latest()->paginate(20)->withQueryString(), 'categories' => Category::ordered()->get(), 'brands' => Brand::ordered()->get()]);
    }

    public function form(string $module, ?int $id = null)
    {
        $config = $this->module($module);
        $entry = $id ? $config['model']::findOrFail($id) : new $config['model'];

        return view('admin.catalog.form', compact('module', 'config', 'entry') + ['categories' => Category::ordered()->get(), 'brands' => Brand::ordered()->get(), 'industries' => Industry::ordered()->get(), 'products' => $module === 'industries' ? Product::ordered()->get(['id', 'name']) : collect()]);
    }

    public function save(Request $request, string $module, ?int $id = null)
    {
        $config = $this->module($module);
        $entry = $id ? $config['model']::findOrFail($id) : new $config['model'];
        $rules = [
            'name' => 'required|string|max:200', 'slug' => ['required', 'string', 'max:200', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique($module, 'slug')->ignore($entry->id)],
            'description' => 'nullable|string|max:20000', 'status' => 'required|in:draft,published',
            'sort_order' => 'required|integer|min:0|max:100000', 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:4096',
            'image_alt' => 'nullable|string|max:255', 'meta_title' => 'nullable|string|max:200', 'meta_description' => 'nullable|string|max:500',
        ];
        if ($module === 'brands') {
            $rules += ['focus' => 'nullable|string|max:255', 'website_url' => 'nullable|url:http,https|max:255'];
        }
        if ($module === 'industries') {
            $rules += ['challenges' => 'nullable|string|max:10000', 'solution_copy' => 'nullable|string|max:10000', 'product_ids' => 'nullable|array', 'product_ids.*' => 'integer|exists:products,id'];
        }
        if ($module === 'products') {
            $rules += [
                'category_id' => 'required|exists:categories,id', 'brand_id' => 'required|exists:brands,id',
                'model_or_series' => 'nullable|string|max:255', 'short_description' => 'required|string|max:1000',
                'benefits_text' => 'nullable|string|max:10000', 'applications_text' => 'nullable|string|max:10000',
                'specifications' => 'nullable|array|max:60', 'specifications.*.label' => 'nullable|string|max:150', 'specifications.*.value' => 'nullable|string|max:500',
                'industry_ids' => 'nullable|array', 'industry_ids.*' => 'integer|exists:industries,id', 'featured' => 'nullable|boolean',
                'datasheet_file' => 'nullable|file|mimes:pdf|extensions:pdf|max:10240',
                'gallery' => 'nullable|array|max:8', 'gallery.*' => 'image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:4096',
                'remove_gallery' => 'nullable|array', 'remove_gallery.*' => 'string|max:255', 'remove_image' => 'nullable|boolean', 'remove_datasheet' => 'nullable|boolean',
            ];
        }
        $data = $request->validate($rules);
        $industryIds = $data['industry_ids'] ?? [];
        $productIds = $data['product_ids'] ?? [];
        if ($module === 'products') {
            foreach (['benefits', 'applications'] as $field) {
                $data[$field] = array_values(array_filter(array_map('trim', preg_split('/\R/', $data[$field.'_text'] ?? ''))));
            }
            $data['specifications'] = array_values(array_filter($data['specifications'] ?? [], fn ($row) => filled($row['label'] ?? null) && filled($row['value'] ?? null)));
            $data['featured'] = $request->boolean('featured');
            $data['gallery'] = array_values(array_diff($entry->gallery ?? [], $data['remove_gallery'] ?? []));
            foreach ($request->file('gallery', []) as $file) {
                $data['gallery'][] = $file->store('catalog', 'public');
            }
            if ($request->boolean('remove_image')) {
                $data['image'] = null;
            }
            if ($request->boolean('remove_datasheet')) {
                $data['datasheet_file'] = null;
            }
        }
        foreach (['image', 'datasheet_file'] as $field) {
            if ($request->hasFile($field) && array_key_exists($field, $rules)) {
                $data[$field] = $request->file($field)->store('catalog', 'public');
            } elseif (! $request->boolean($field === 'image' ? 'remove_image' : 'remove_datasheet')) {
                unset($data[$field]);
            }
        }
        unset($data['industry_ids'], $data['product_ids'], $data['benefits_text'], $data['applications_text'], $data['remove_gallery'], $data['remove_image'], $data['remove_datasheet']);
        DB::transaction(function () use ($entry, $data, $module, $industryIds, $productIds) {
            $entry->fill($data)->save();
            if ($module === 'products') {
                $entry->industries()->sync($industryIds);
            }
            if ($module === 'industries') {
                $entry->products()->sync($productIds);
            }
        });

        return redirect()->route('admin.catalog.index', $module)->with('success', $config['label'].' berhasil disimpan.');
    }

    public function destroy(string $module, int $id)
    {
        $config = $this->module($module);
        $entry = $config['model']::findOrFail($id);
        if (in_array($module, ['categories', 'brands'], true) && $entry->products()->exists()) {
            return back()->withErrors(['delete' => 'Pindahkan produk terkait sebelum menghapus data ini.']);
        }
        $entry->delete();

        return redirect()->route('admin.catalog.index', $module)->with('success', 'Data berhasil dihapus.');
    }
}
