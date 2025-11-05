<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\Produk; // <-- PENTING: Tambahkan model Produk
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    /**
     * Menampilkan halaman 'About Us' untuk publik.
     * (Sekarang juga menampilkan produk)
     */
    public function show()
    {
        // 1. Ambil data 'About Us' (Info Toko, Narasi, Foto)
        $about = AboutUs::firstOrFail();

        // 2. Ambil data Produk (sesuai sketsa: produk terbaru)
        // Kita paginasi 5 produk per halaman
        $products = Produk::latest()->paginate(5); // 'latest()' = buku baru launching

        // 3. Kirim *kedua* data ke view
        return view('public.about-us', compact('about', 'products'));
    }

    /**
     * Menampilkan form edit 'About Us' untuk admin.
     * (Halaman ini HANYA meng-edit info toko, bukan produk)
     */
    public function edit()
    {
        $about = AboutUs::firstOrFail();
        return view('admin.about-us.edit', compact('about'));
    }

    /**
     * Meng-update data 'About Us'.
     */
    public function update(Request $request)
    {
        // Validasi
        $request->validate([
            'store_name' => 'required|string|max:255',
            'narration' => 'required|string|min:20', // min 5 kalimat (kira-kira)
            'building_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $about = AboutUs::firstOrFail();

        // Siapkan data
        $data = [
            'store_name' => $request->store_name,
            'narration' => $request->narration,
        ];

        // Cek jika ada file gambar baru
        if ($request->hasFile('building_photo')) {
            // Hapus gambar lama jika ada
            if ($about->building_photo && Storage::disk('public')->exists($about->building_photo)) {
                Storage::disk('public')->delete($about->building_photo);
            }

            // Simpan gambar baru
            $path = $request->file('building_photo')->store('images/about-us', 'public');
            $data['building_photo'] = $path;
        }

        // Update data
        $about->update($data);

        return redirect()->route('admin.about.edit')->with('success', 'Halaman "About Us" berhasil diperbarui!');
    }
}
