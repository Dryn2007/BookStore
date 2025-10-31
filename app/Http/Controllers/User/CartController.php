<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function index()
    {
        $items = Cart::with('produk')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.cart', compact('items'));
    }

    public function add($id)
    {
        \App\Models\Cart::create([
            'user_id' => Auth::id(),
                'produk_id' => $id,
                'jumlah' => 1
            ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }


    /**
     * Method remove() Anda, tidak berubah.
     */
    public function remove($id)
    {
        \App\Models\Cart::where('id', $id)->where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function update(Request $request)
    {
        if ($request->has('increase')) {
            $item = Cart::findOrFail($request->increase);
            $item->jumlah += 1;
            $item->save();
        }

        if ($request->has('decrease')) {
            $item = Cart::findOrFail($request->decrease);
            if ($item->jumlah > 1) {
                $item->jumlah -= 1;
                $item->save();
            }
        }

        return redirect()->back();
    }
}
