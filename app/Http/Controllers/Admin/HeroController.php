<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    private function defaults(string $focus): array
    {
        $slide = collect(config('focus-slides'))->firstWhere('id', $focus);
        abort_unless($slide, 404);

        return $slide;
    }

    public function index()
    {
        return view('admin.hero.index', ['slides' => HeroSlide::slides()]);
    }

    public function edit(string $focus)
    {
        return view('admin.hero.edit', [
            'default' => $this->defaults($focus),
            'slide' => collect(HeroSlide::slides())->firstWhere('id', $focus),
            'entry' => HeroSlide::where('focus_key', $focus)->first(),
        ]);
    }

    public function update(Request $request, string $focus)
    {
        $this->defaults($focus);
        $data = $request->validate([
            'nav_label' => 'required|string|max:60',
            'eyebrow' => 'required|string|max:80',
            'title_line_1' => 'required|string|max:100',
            'title_line_2' => 'nullable|string|max:100',
            'description' => 'required|string|max:350',
            'image' => 'nullable|prohibited_if:reset_image,1|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:4096|dimensions:max_width=8000,max_height=8000',
            'reset_image' => 'nullable|boolean',
        ]);
        unset($data['reset_image'], $data['image']);
        $data['title_line_2'] = $data['title_line_2'] ?? null;
        $uploaded = null;
        try {
            if ($request->hasFile('image')) {
                [$width, $height] = getimagesize($request->file('image')->getRealPath());
                $uploaded = $request->file('image')->store('hero', 'public');
                abort_unless($uploaded, 500, 'Gambar tidak berhasil disimpan.');
                $data += ['image' => $uploaded, 'image_width' => $width, 'image_height' => $height];
            } elseif ($request->boolean('reset_image')) {
                $data += ['image' => null, 'image_width' => null, 'image_height' => null];
            }
            HeroSlide::updateOrCreate(['focus_key' => $focus], $data);
        } catch (\Throwable $exception) {
            if ($uploaded) {
                Storage::disk('public')->delete($uploaded);
            }
            throw $exception;
        }

        return redirect()->route('admin.hero.index')->with('success', 'Hero Beranda berhasil disimpan.');
    }
}
