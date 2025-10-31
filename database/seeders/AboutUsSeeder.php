<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutUs;

class AboutUsSeeder extends Seeder
{
    public function run(): void
    {
        AboutUs::create([
            'store_name' => 'Tentang Kami (Default)',
            'narration' => 'Ini adalah paragraf default tentang kami. Silakan edit di halaman admin.',
            'building_photo' => null, // atau 'images/default.jpg' jika ada
        ]);
    }
}
