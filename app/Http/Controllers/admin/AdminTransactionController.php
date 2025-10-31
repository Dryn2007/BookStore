<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class AdminTransactionController extends Controller
{
    // AdminTransactionController.php

    // AdminTransactionController.php

    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses hanya untuk admin.');
        }

        $transactions = Transaction::with(['user', 'items.produk'])->latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    public function konfirmasi(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses hanya untuk admin.');
        }

        $request->validate([
            'status' => 'required|in:pending,dikirim,selesai',
            'catatan_pengiriman' => 'nullable|string|max:500',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'status' => $request->status,
            'catatan_pengiriman' => $request->catatan_pengiriman,
        ]);

        return back()->with('success', 'Status pesanan dan catatan pengiriman telah diperbarui.');
    }
}
