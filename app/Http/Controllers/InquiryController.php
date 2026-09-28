<?php

namespace App\Http\Controllers;

use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150', 'company' => 'required|string|max:200',
            'email' => 'required|email|max:254', 'phone' => ['required', 'string', 'max:30', 'regex:/^[+0-9() .-]{7,30}$/'],
            'product_id' => 'nullable|integer', 'application' => 'nullable|string|max:2000',
            'message' => 'required|string|min:10|max:5000', 'consent' => 'accepted',
            'website' => 'nullable|prohibited', 'source_page' => ['nullable', 'string', 'max:255', 'regex:#^/(?!/)[a-zA-Z0-9/_?=&%.-]*$#'],
        ]);
        if (! empty($data['product_id']) && ! Product::published()->whereKey($data['product_id'])->exists()) {
            throw ValidationException::withMessages(['product_id' => 'Produk tidak tersedia. Silakan pilih produk lain.']);
        }
        unset($data['consent'], $data['website']);
        foreach (['name', 'company', 'application', 'message'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = trim(strip_tags($data[$field]));
            }
        }
        if (strlen($data['message']) < 10 || $data['name'] === '' || $data['company'] === '') {
            throw ValidationException::withMessages(['message' => 'Isi nama, perusahaan, dan pesan dengan teks yang valid.']);
        }
        $inquiry = Inquiry::create($data + ['reference_number' => 'ART-'.now()->format('Ymd').'-'.strtoupper((string) Str::ulid()), 'consent_at' => now()]);
        try {
            Mail::to(Setting::values()['inquiry_email'])->send(new InquiryReceived($inquiry));
            $inquiry->update(['notified_at' => now()]);
        } catch (\Throwable $exception) {
            Log::error('Inquiry notification failed', ['reference' => $inquiry->reference_number, 'exception_type' => $exception::class]);
        }

        return redirect()->route('contact')->with('inquiry_reference', $inquiry->reference_number);
    }
}
