<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    public const CATEGORIES = ['legalitas' => 'Legalitas', 'perusahaan' => 'Publikasi Perusahaan'];

    protected $fillable = [
        'title', 'description', 'category', 'file_path', 'original_filename', 'mime_type',
        'file_size', 'thumbnail_path', 'thumbnail_mime_type', 'status', 'allow_download',
        'sort_order', 'published_at', 'is_demo', 'demo_key',
    ];

    protected $attributes = ['status' => 'draft', 'allow_download' => false, 'sort_order' => 0, 'is_demo' => false];

    protected function casts(): array
    {
        return ['allow_download' => 'boolean', 'is_demo' => 'boolean', 'published_at' => 'datetime'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where(fn (Builder $q) => $q->where('is_demo', false)
                ->when(app()->environment(['local', 'staging', 'testing']),
                    fn (Builder $q) => $q->orWhere(fn (Builder $q) => $q->where('is_demo', true)->where('category', 'perusahaan'))));
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at')->orderBy('id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getFileTypeAttribute(): string
    {
        return match ($this->mime_type) {
            'application/pdf' => 'PDF', 'image/jpeg' => 'JPG', 'image/png' => 'PNG', 'image/webp' => 'WEBP', default => 'Dokumen',
        };
    }
}
