<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class BrandProfileDraftSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('Draft brand hanya boleh diisi pada lingkungan local/testing.');
        }

        $drafts = json_decode(file_get_contents(database_path('data/brand-profile-drafts.json')), true, 512, JSON_THROW_ON_ERROR);

        DB::transaction(function () use ($drafts) {
            $pending = [];
            foreach ($drafts as $draft) {
                $brand = Brand::where('slug', $draft['slug'])->lockForUpdate()->first();
                // Only enrich untouched starter copy; reruns and CMS edits always win.
                if (! $brand || $brand->description !== $draft['expected_description'] || filled($brand->technology_details)) {
                    $this->command?->line('Lewati: '.$draft['slug'].' (sudah diisi, diedit, atau tidak ada).');
                    continue;
                }
                $pending[] = ['brand' => $brand, 'draft' => $draft];
            }

            if ($pending === []) {
                return;
            }

            $backup = 'brand-content-backups/'.now()->format('Ymd-His').'-'.Str::uuid().'.json';
            $originals = array_map(fn ($item) => $item['brand']->only(['id', 'slug', 'description', 'technology_details', 'updated_at']), $pending);
            if (! Storage::disk('local')->put($backup, json_encode($originals, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR))) {
                throw new RuntimeException('Backup gagal; konten brand tidak diubah.');
            }

            foreach ($pending as ['brand' => $brand, 'draft' => $draft]) {
                $brand->update([
                    'description' => $draft['hero']."\n\n".$draft['profile'],
                    'technology_details' => $draft['technology_details'],
                ]);
                $this->command?->info('Draft diisi: '.$brand->slug);
            }
            $this->command?->line('Backup privat: '.$backup);
        });
    }
}
