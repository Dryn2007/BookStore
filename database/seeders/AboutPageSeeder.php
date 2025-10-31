<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutPage;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        AboutPage::create([
            'store_name' => 'Nama Toko Default',
            'narrative' => 'Ini adalah narasi default tentang toko kami. Silakan edit di halaman admin. Tulis setidaknya 5 kalimat di sini untuk menjelaskan visi dan misi toko Anda.',
            'building_image' => null,
        ]);
    }
}
