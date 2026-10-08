<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    protected $fillable = [
        'focus_key', 'nav_label', 'eyebrow', 'title_line_1', 'title_line_2',
        'description', 'image', 'image_width', 'image_height',
    ];

    public static function slides(): array
    {
        $entries = static::all()->keyBy('focus_key');

        // Keep the five existing identities, order, and visual settings from config.
        return array_map(function (array $slide) use ($entries) {
            $entry = $entries->get($slide['id']);
            if (! $entry) {
                return $slide;
            }
            $slide['navLabel'] = $entry->nav_label;
            $slide['eyebrow'] = $entry->eyebrow;
            $slide['title'] = array_values(array_filter([$entry->title_line_1, $entry->title_line_2], fn ($line) => filled($line)));
            $slide['description'] = $entry->description;
            if ($entry->image && Storage::disk('public')->exists($entry->image)) {
                $slide['image'] = Storage::disk('public')->url($entry->image);
                $slide['width'] = $entry->image_width;
                $slide['height'] = $entry->image_height;
            }

            return $slide;
        }, config('focus-slides'));
    }
}
