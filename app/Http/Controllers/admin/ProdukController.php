<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         (new \App\Http\Middleware\RoleMiddleware)->handle($request, function () {}, 'admin');
    //         return $next($request);
    //     });
    // }

    public function index()
    {
        $produks = Produk::with('kategori')->get();
        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'foto' => 'required|image|max:2048',
            'kategori_id' => 'required|exists:kategoris,id'
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::all();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
            'kategori_id' => 'required|exists:kategoris,id'
        ]);

        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate');
    }

    public function updateStock(Request $request, Produk $produk)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $produk->update([
            'stock' => $request->stock
        ]);

        return redirect()->back()->with('success', 'Stock updated successfully');
    }



    public function destroy(Produk $produk)
    {
        if (!$produk->canBeDeleted()) {
            return redirect()->back()->with('error', 'Cannot delete Produk with remaining stock');
        }

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus');
    }
}
