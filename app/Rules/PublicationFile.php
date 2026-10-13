<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class PublicationFile implements ValidationRule
{
    public function __construct(private bool $imageOnly = false) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $fail('File tidak dapat dibaca. Silakan unggah ulang.');

            return;
        }
        $extension = strtolower($value->getClientOriginalExtension());
        $types = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
        if (! $this->imageOnly) {
            $types['pdf'] = 'application/pdf';
        }
        $mime = $value->getMimeType();
        if (! isset($types[$extension]) || $types[$extension] !== $mime) {
            $fail('Ekstensi dan isi file harus sesuai: '.($this->imageOnly ? 'JPG, PNG, atau WEBP.' : 'PDF, JPG, PNG, atau WEBP.'));

            return;
        }
        $path = $value->getRealPath();
        if ($mime === 'application/pdf') {
            $handle = fopen($path, 'rb');
            $header = fread($handle, 8);
            fseek($handle, max(0, $value->getSize() - 1024));
            $tail = stream_get_contents($handle);
            fclose($handle);
            if (! preg_match('/^%PDF-(?:1\.[0-7]|2\.0)/', $header) || ! preg_match('/%%EOF\s*$/', $tail)) {
                $fail('Signature PDF tidak valid atau file tidak lengkap.');
            }
        } else {
            $info = @getimagesize($path);
            if (! $info || ($info['mime'] ?? '') !== $mime || $info[0] > 8000 || $info[1] > 8000 || $info[0] * $info[1] > 20000000) {
                $fail('Gambar tidak valid atau dimensinya terlalu besar (maksimal 8000 px dan 20 megapiksel).');
            }
        }
    }
}
