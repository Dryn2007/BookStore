<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN INI

class TransactionController extends Controller
{
    public function checkoutForm()
    {
        $items = Cart::with('produk')->where('user_id', auth()->id())->get();
        return view('user.checkout', compact('items'));
    }

    /**
     * Ganti method processCheckout Anda dengan yang ini.
     * Ini sudah termasuk Pengecekan Stok dan Database Transaction.
     */
    public function processCheckout(Request $request)
    {
        // 1. Validasi input form
        $request->validate([
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);

        // Memulai Database Transaction
        DB::beginTransaction();

        try {
            $user = auth()->user();
            // Ambil item keranjang DENGAN relasi produk
            $items = $user->cart()->with('produk')->get();

            if ($items->isEmpty()) {
                return redirect()->route('user.cart')->with('error', 'Keranjang kosong!');
            }

            // --- TAHAP 1: VALIDASI STOK ---
            // Cek stok semua item SEBELUM memproses
            foreach ($items as $item) {
                // Pastikan relasi 'produk' ada dan model Produk punya method 'hasEnoughStock'
                if (!$item->produk) {
                    throw new \Exception("Produk dalam keranjang tidak valid.");
                }

                if (!$item->produk->hasEnoughStock($item->jumlah)) {
                    // Jika satu saja stok tidak cukup, lempar error dan proses akan di-rollback
                    throw new \Exception("Stok tidak cukup untuk: {$item->produk->nama}");
                }
            }

            // --- TAHAP 2: PROSES TRANSAKSI ---

            // Hitung total (setelah yakin stok ada)
            $total = $items->sum(function ($item) {
                return $item->produk->harga * $item->jumlah;
            });

            // Simpan ke transactions
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'metode_pembayaran' => $request->metode_pembayaran,
                'total' => $total,
                'status' => 'pending',
            ]);

            // Simpan ke transaction_items DAN kurangi stok
            foreach ($items as $item) {
                TransactionItem::create([
                    'user_id' => auth()->id(), // (Anda punya ini di kode asli)
                    'transaction_id' => $transaction->id,
                    'produk_id' => $item->produk_id,
                    'jumlah' => $item->jumlah,
                    'harga' => $item->produk->harga,
                ]);

                // KURANGI STOK PRODUK
                // 'decrement' adalah operasi database yang aman
                $item->produk->decrement('stock', $item->jumlah);
            }

            // Hapus cart
            $user->cart()->delete();

            // Jika semua berhasil, simpan perubahan ke database
            DB::commit();

            return redirect()->route('user.transactions')->with('success', 'Checkout berhasil! Pesanan sedang diproses.');
        } catch (\Exception $e) {
            // Jika terjadi error di tahap manapun, batalkan semua perubahan
            DB::rollBack();
            // Kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with(['items.produk.reviews' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.transactions', ['transaksi' => $transactions]);
    }

    public function terimaPesanan($id)
    {
        $transaction = Transaction::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Hanya izinkan update jika statusnya 'dikirim' (contoh)
        if ($transaction->status == 'dikirim') {
            $transaction->update(['status' => 'selesai']);
            return back()->with('success', 'Pesanan selesai.');
        }

        return back()->with('error', 'Tidak dapat mengubah status pesanan.');
    }
}
