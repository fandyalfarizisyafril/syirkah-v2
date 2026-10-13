<?php

namespace Database\Seeders;

use App\Models\Publication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicationDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'staging', 'testing'])) {
            throw new \RuntimeException('Demo publikasi hanya boleh dibuat di local, staging, atau testing.');
        }
        $items = [
            ['key' => 'website-guide', 'title' => 'Panduan Penggunaan Website SMART (Demo)', 'category' => 'perusahaan', 'status' => 'published', 'lines' => [
                'Materi pengujian fitur publikasi. Bukan dokumen resmi perusahaan.',
                'Beranda memperkenalkan bidang produk dan informasi perusahaan.',
                'Tentang Kami menampilkan profil dan nilai perusahaan.',
                'Produk & Solusi menyediakan pencarian dan filter katalog.',
                'Brands & Principals menghubungkan brand dengan produk terkait.',
                'Publikasi memuat dokumen yang disetujui untuk akses publik.',
                'Hubungi SMART melalui halaman Kontak untuk kebutuhan informasi.',
            ]],
            ['key' => 'publication-sample', 'title' => 'Contoh Materi Publikasi (Demo)', 'category' => 'perusahaan', 'status' => 'draft', 'lines' => [
                'Materi pengujian upload, preview, dan pengaturan akses dokumen.',
                'File ini bukan company profile atau katalog resmi perusahaan.',
            ]],
            ['key' => 'administration-sample', 'title' => 'Contoh Dokumen Administrasi (Demo)', 'category' => 'legalitas', 'status' => 'draft', 'lines' => [
                'Placeholder untuk menguji kategori Legalitas dalam Admin CMS.',
                'Bukan NPWP, NIB, SK, sertifikat, atau bukti legalitas perusahaan.',
                'Tidak berisi nomor dokumen, QR code, tanda tangan, atau stempel.',
            ]],
        ];
        foreach ($items as $item) {
            // Demo keys prevent reruns from replacing content edited in the CMS.
            if (Publication::where('demo_key', $item['key'])->exists()) {
                continue;
            }
            $path = 'documents/'.Str::uuid().'.pdf';
            $pdf = self::pdf($item['title'], $item['lines'], $item['category'] === 'legalitas');
            try {
                Storage::disk('publications')->put($path, $pdf);
                DB::transaction(fn () => Publication::create([
                    'title' => $item['title'], 'description' => 'Dokumen DEMO untuk pengujian website. Bukan dokumen resmi perusahaan.',
                    'category' => $item['category'], 'status' => $item['status'],
                    'file_path' => $path, 'original_filename' => $item['key'].'-demo.pdf',
                    'mime_type' => 'application/pdf', 'file_size' => strlen($pdf),
                    'is_demo' => true, 'demo_key' => $item['key'],
                    'allow_download' => $item['status'] === 'published',
                    'published_at' => $item['status'] === 'published' ? now() : null,
                ]));
            } catch (\Throwable $exception) {
                Storage::disk('publications')->delete($path);
                throw $exception;
            }
        }
    }

    public static function pdf(string $title = 'Pengujian Publikasi (Demo)', array $lines = [], bool $legal = false): string
    {
        $escape = fn (string $text) => str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
        $watermark = 'DEMO '.chr(151).' BUKAN DOKUMEN '.($legal ? 'LEGALITAS' : 'RESMI');
        $stream = '0.88 g BT /F1 22 Tf 36 420 Td ('.$escape($watermark).") Tj ET\n";
        $stream .= '0.04 0.15 0.24 rg BT /F1 19 Tf 48 770 Td 27 TL ';
        foreach (explode("\n", wordwrap($title, 45)) as $line) {
            $stream .= '('.$escape($line).') Tj T* ';
        }
        $stream .= "ET\n0.2 g BT /F1 11 Tf 48 660 Td 20 TL ";
        foreach ($lines as $line) {
            foreach (explode("\n", wordwrap($line, 78)) as $part) {
                $stream .= '('.$escape($part).') Tj T* ';
            }
            $stream .= 'T* ';
        }
        $stream .= "ET\nBT /F1 10 Tf 48 55 Td (Hanya untuk pengujian lokal atau staging.) Tj ET\n";
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
            '<< /Length '.strlen($stream).">>\nstream\n".$stream.'endstream',
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n".$object."\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 6\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        return $pdf."trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n".$xref."\n%%EOF\n";
    }
}
