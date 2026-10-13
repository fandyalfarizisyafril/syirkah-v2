<?php

namespace App\Console\Commands;

use App\Models\Publication;
use App\Services\PublicationStorage;
use Illuminate\Console\Command;

class ClearPublicationDemos extends Command
{
    protected $signature = 'publications:clear-demo {--force : Hapus tanpa konfirmasi interaktif}';

    protected $description = 'Hapus hanya publikasi bertanda demo beserta file private miliknya';

    public function handle(PublicationStorage $storage): int
    {
        $count = Publication::where('is_demo', true)->count();
        if (! $this->option('force') && ! $this->confirm('Hapus '.$count.' publikasi DEMO beserta filenya?', false)) {
            return self::SUCCESS;
        }
        foreach (Publication::where('is_demo', true)->cursor() as $entry) {
            // Revoke access before attempting filesystem cleanup, including on IO failure.
            $entry->update(['status' => 'draft']);
            $storage->delete($entry->file_path);
            $storage->delete($entry->thumbnail_path);
            $entry->delete();
        }
        $this->info($count.' publikasi demo dihapus. Dokumen non-demo tidak berubah.');

        return self::SUCCESS;
    }
}
