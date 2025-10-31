<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $produkId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
        ]);

        $produk = Produk::findOrFail($produkId);

        // Check if user has purchased and received this product
        $hasPurchasedAndReceived = \App\Models\Transaction::where('user_id', Auth::id())
            ->where('status', 'selesai') // Only allow review after order is completed/received
            ->whereHas('items', function ($query) use ($produkId) {
                $query->where('produk_id', $produkId);
            })
            ->exists();

        if (!$hasPurchasedAndReceived) {
            return back()->with('error', 'Anda hanya dapat memberikan review setelah menerima produk yang telah dibeli.');
        }

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', Auth::id())
            ->where('produk_id', $produkId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        // Create review
        Review::create([
            'user_id' => Auth::id(),
            'produk_id' => $produkId,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Update product rating and total reviews
        $this->updateProductRating($produkId);

        return back()->with('success', 'Review berhasil ditambahkan!');
    }

    public function update(Request $request, $produkId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
        ]);

        $review = Review::where('user_id', Auth::id())
            ->where('produk_id', $produkId)
            ->firstOrFail();

        $review->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Update product rating and total reviews
        $this->updateProductRating($produkId);

        return back()->with('success', 'Review berhasil diperbarui!');
    }

    private function updateProductRating($produkId)
    {
        $reviews = Review::where('produk_id', $produkId)->get();

        if ($reviews->count() > 0) {
            $averageRating = $reviews->avg('rating');
            $totalReviews = $reviews->count();

            Produk::where('id', $produkId)->update([
                'rating' => round($averageRating, 1),
                'total_reviews' => $totalReviews,
            ]);
        } else {
            Produk::where('id', $produkId)->update([
                'rating' => 0,
                'total_reviews' => 0,
            ]);
        }
    }

    public function index($produkId)
    {
        $produk = Produk::with('reviews.user')->findOrFail($produkId);
        return view('user.reviews', compact('produk'));
    }

    public function getReviews($produkId)
    {
        $reviews = Review::with('user')
            ->where('produk_id', $produkId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['reviews' => $reviews]);
    }
}
