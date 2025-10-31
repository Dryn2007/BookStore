<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\Produk; // <-- PASTIKAN ANDA SUDAH PUNYA MODEL INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{
    /**
     * Menampilkan halaman 'About Us' untuk publik (SESUAI SKETSA)
     */
    public function show()
    {
        // 1. Ambil data 'About' (Nama toko, narasi, gambar gedung)
        $about = AboutPage::firstOrFail(); // Ambil 1 baris data

        // 2. Ambil data 'Produk' (Buku terbaru, dengan pagination)
        // Sesuai sketsa: "buku terbaru baru launching" -> order by created_at DESC
        $products = Produk::latest()->paginate(5); // 5 produk per halaman

        return view('public.about-us', compact('about', 'products'));
    }

    /**
     * Menampilkan form edit 'About Us' untuk admin.
     */
    public function edit()
    {
        $about = AboutPage::firstOrFail();
        return view('admin.about-us-edit', compact('about'));
    }

    /**
     * Meng-update data 'About Us'.
     */
    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'narrative' => 'required|string|min:20', // Sesuai sketsa "min 5 kalimat"
            'building_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $about = AboutPage::firstOrFail();

        $data = $request->only('store_name', 'narrative');

        if ($request->hasFile('building_image')) {
            // Hapus gambar lama
            if ($about->building_image && Storage::disk('public')->exists($about->building_image)) {
                Storage::disk('public')->delete($about->building_image);
            }

            // Simpan gambar baru
            $path = $request->file('building_image')->store('images/about-page', 'public');
            $data['building_image'] = $path;
        }

        $about->update($data);

        return redirect()->route('admin.about.edit')->with('success', 'Halaman "About Us" berhasil diperbarui!');
    }
}
