<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryMedia extends Model
{
    protected $table = 'gallery_media';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['width' => 'integer', 'height' => 'integer', 'sort_order' => 'integer'];
    }

    public function getDisplayTitleAttribute(): string
    {
        return $this->title ?: ($this->source === 'photo' ? 'Foto perusahaan' : 'Video perusahaan');
    }

    public function getCategoryLabelAttribute(): string
    {
        return $this->source === 'photo' ? 'Foto' : 'Video Perusahaan';
    }
}
