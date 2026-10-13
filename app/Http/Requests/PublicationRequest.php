<?php

namespace App\Http\Requests;

use App\Models\Publication;
use App\Rules\PublicationFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class PublicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-content') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:3000'],
            'category' => ['required', Rule::in(array_keys(Publication::CATEGORIES))],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'allow_download' => ['required', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'published_at' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'document' => ['bail', $this->route('publication') ? 'nullable' : 'required', 'file', 'max:'.config('publications.max_upload_kb'), new PublicationFile],
            'thumbnail' => ['bail', 'nullable', 'file', 'max:'.config('publications.max_thumbnail_kb'), new PublicationFile(true)],
            'remove_thumbnail' => ['nullable', 'boolean'],
            'legal_confirmation' => [Rule::when($this->input('category') === 'legalitas' && $this->input('status') === 'published', ['required', 'accepted'], ['exclude'])],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            if ($this->route('publication')?->is_demo && $this->input('status') === 'published'
                && ($this->input('category') === 'legalitas' || ! app()->environment(['local', 'staging', 'testing']))) {
                $validator->errors()->add('status', 'Dokumen demo hanya boleh dipublikasikan sebagai Publikasi Perusahaan pada local atau staging.');
            }
        }];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Pilih file dokumen sebelum menyimpan.',
            'document.max' => 'Ukuran dokumen melebihi batas '.config('publications.max_upload_kb').' KB.',
            'thumbnail.max' => 'Ukuran thumbnail melebihi batas '.config('publications.max_thumbnail_kb').' KB.',
            'legal_confirmation.required' => 'Konfirmasi persetujuan publikasi dokumen legalitas wajib dicentang.',
            'legal_confirmation.accepted' => 'Konfirmasi persetujuan publikasi dokumen legalitas wajib dicentang.',
        ];
    }
}
