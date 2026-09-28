<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['data' => 'array'];
    }

    public static function defaults(): array
    {
        return [
            'company_name' => 'PT. Syirkah Mandiri Artomoro',
            'logo' => '',
            'tagline' => 'General Supplier & Technical Solutions',
            'profile' => 'PT. Syirkah Mandiri Artomoro menyediakan equipment, spare part, dan solusi teknis untuk kebutuhan industri. Kami membantu menghubungkan kebutuhan operasional pelanggan dengan produk dan teknologi yang sesuai.',
            'address' => 'Jalan Teladan No. 7, RT 04/RW 10, Kel. Simpang Baru, Kec. Bina Widya, Kota Pekanbaru, Riau 28293.',
            'phone' => '+62 812-6672-3815',
            'email' => 'admin@smartomoro.com',
            'sales_email' => 'doni.rahmat@smartomoro.com',
            'whatsapp_number' => '6281266723815',
            'inquiry_email' => 'admin@smartomoro.com',
            'map_url' => '',
            'legal_information' => '',
            'meta_title' => 'Artomoro | Industrial Supply & Technical Solutions',
            'meta_description' => 'Equipment dan solusi teknis untuk engineering, mechanical, electrical, instrumentation, serta oil spill response. Berbasis di Pekanbaru, Riau.',
        ];
    }

    public static function values(): array
    {
        return array_replace(self::defaults(), static::find(1)?->data ?? []);
    }
}
