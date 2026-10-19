<?php

namespace App\Http\Requests;

use App\Rules\PublicationFile;
use App\Services\BrandVideo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class GalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-content') ?? false;
    }

    public function rules(): array
    {
        $entry = $this->route('medium');
        $photo = $this->input('source') === 'photo';

        return [
            'title' => 'nullable|string|max:200', 'description' => 'nullable|string|max:1500',
            'source' => 'required|in:photo,mp4,youtube', 'status' => 'required|in:draft,published',
            'sort_order' => 'nullable|integer|min:0|max:100000',
            'width' => 'nullable|integer|min:1|max:8192', 'height' => 'nullable|integer|min:1|max:8192',
            'media_file' => [Rule::requiredIf(fn () => in_array($this->input('source'), ['photo', 'mp4'], true) && (! $entry || $entry->source !== $this->input('source'))),
                'nullable', 'file', $photo ? 'max:8192' : 'max:51200',
                $photo ? new PublicationFile(true) : function ($attribute, $value, $fail) {
                    if (! $value instanceof UploadedFile || ! $value->isValid() || strtolower($value->getClientOriginalExtension()) !== 'mp4' || $value->getMimeType() !== 'video/mp4'
                        || substr(file_get_contents($value->getRealPath(), false, null, 0, 12), 4, 4) !== 'ftyp') {
                        $fail('Gunakan video MP4 dengan ekstensi dan isi file yang valid.');
                    }
                }],
            'youtube_url' => ['nullable', 'required_if:source,youtube', 'string', 'max:500', function ($attribute, $value, $fail) {
                if (! BrandVideo::youtubeId($value)) {
                    $fail('Gunakan URL HTTPS YouTube yang valid.');
                }
            }],
            'poster' => ['nullable', 'file', 'max:4096', new PublicationFile(true)],
            'remove_poster' => 'nullable|boolean',
        ];
    }
}
