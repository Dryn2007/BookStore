<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('produk.kategori')
            ->get();

        return view('user.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request, $produkId)
    {
        $userId = Auth::id();
        $wishlist = Wishlist::where('user_id', $userId)
            ->where('produk_id', $produkId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['inWishlist' => false]);
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'produk_id' => $produkId,
            ]);
            return response()->json(['inWishlist' => true]);
        }
    }

    public function remove($produkId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('produk_id', $produkId)
            ->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
