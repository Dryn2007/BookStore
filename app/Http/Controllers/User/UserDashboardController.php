<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Pastikan ini ada
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Message;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dengan eager loading 'kategori' dan 'reviews'
        // Ini menggantikan dua blok query terpisah yang Anda miliki sebelumnya
        $query = Produk::with(['kategori', 'reviews.user']);

        // Filter by search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter by rating minimum
        if ($request->has('rating_min') && $request->rating_min != '') {
            $query->where('rating', '>=', (float) $request->rating_min);
        }

        // Filter by price maximum
        if ($request->has('harga_max') && $request->harga_max != '') {
            $query->where('harga', '<=', (int) $request->harga_max);
        }

        // Sorting
        if ($request->has('sort') && $request->sort != '') {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('harga', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('harga', 'desc');
                    break;
                case 'rating_desc':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'terlaris':
                    $query->withCount('transactionItems')
                        ->orderBy('transaction_items_count', 'desc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->latest();
        }

        // Ambil semua kategori untuk dropdown filter
        $kategori = Kategori::all();

        // Eksekusi query dan dapatkan hasilnya
        $perPage = 6;
        $produk = $query->paginate($perPage);

        // Cek apakah user punya pesan baru dari admin
        $unreadMessagesCount = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        if ($unreadMessagesCount > 0) {
            session()->put('user_unread_messages_count', $unreadMessagesCount);
        } else {
            session()->forget('user_unread_messages_count');
        }

        return view('user.dashboard', compact('produk', 'kategori'));
    }

    public function autoSuggest(Request $request)
    {
        $query = $request->get('q');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Produk::where('nama', 'like', '%' . $query . '%')
            ->limit(5)
            ->get(['id', 'nama']);

        return response()->json($suggestions);
    }
}
