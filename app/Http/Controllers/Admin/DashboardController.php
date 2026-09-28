<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', ['counts' => ['Produk' => Product::count(), 'Brand' => Brand::count(), 'Konten draft' => Product::where('status', 'draft')->count() + Category::where('status', 'draft')->count() + Brand::where('status', 'draft')->count() + Industry::where('status', 'draft')->count()], 'newInquiries' => Inquiry::where('status', 'new')->count(), 'recent' => auth()->user()->can('manage-inquiries') ? Inquiry::latest()->limit(6)->get() : collect()]);
    }

    public function inquiries(Request $request)
    {
        $filters = $request->validate(['status' => ['nullable', Rule::in(Inquiry::STATUSES)], 'q' => 'nullable|string|max:150']);
        $entries = Inquiry::with('product')->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['q'] ?? null, fn ($q, $term) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$term.'%')->orWhere('company', 'like', '%'.$term.'%')->orWhere('reference_number', 'like', '%'.$term.'%')))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.inquiries.index', compact('entries'));
    }

    public function inquiry(Inquiry $inquiry)
    {
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function updateInquiry(Request $request, Inquiry $inquiry)
    {
        $inquiry->update($request->validate(['status' => ['required', Rule::in(Inquiry::STATUSES)], 'internal_notes' => 'nullable|string|max:10000']));

        return back()->with('success', 'Inquiry berhasil diperbarui.');
    }

    public function settings()
    {
        return view('admin.settings', ['settings' => Setting::values()]);
    }

    public function updateSettings(Request $request)
    {
        $rules = [];
        foreach (array_keys(Setting::defaults()) as $field) {
            if ($field !== 'logo') {
                $rules[$field] = 'nullable|string|max:20000';
            }
        }
        $rules = array_replace($rules, [
            'company_name' => 'required|string|max:200', 'tagline' => 'required|string|max:255',
            'email' => 'required|email|max:254', 'sales_email' => 'nullable|email|max:254', 'inquiry_email' => 'required|email|max:254',
            'phone' => ['required', 'string', 'max:30', 'regex:/^[+0-9() .-]{7,30}$/'], 'whatsapp_number' => ['nullable', 'regex:/^[1-9][0-9]{7,14}$/'],
            'map_url' => 'nullable|url:http,https|max:255', 'meta_title' => 'required|string|max:200', 'meta_description' => 'required|string|max:500',
        ]);
        $rules['company_logo'] = 'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:4096';
        $data = $request->validate($rules);
        unset($data['company_logo']);
        if ($request->hasFile('company_logo')) {
            $data['logo'] = $request->file('company_logo')->store('company', 'public');
        }
        Setting::updateOrCreate(['id' => 1], ['data' => array_replace(Setting::values(), $data)]);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
