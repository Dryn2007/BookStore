@extends('layouts.user')

@section('content')
    <div class="bg-pink-50 dark:bg-gray-800 shadow p-6 max-w-4xl mx-auto mt-10 rounded-lg transition-colors duration-300">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-pink-800 dark:text-pink-200">Review Produk</h1>
                <h2 class="text-lg text-pink-600 dark:text-pink-400">{{ $produk->nama }}</h2>
            </div>
            <a href="{{ route('user.dashboard') }}" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition-colors duration-200">
                ← Kembali ke Dashboard
            </a>
        </div>

        <!-- Product Info -->
        <div class="bg-white dark:bg-gray-700 rounded-lg p-4 mb-6 shadow">
            <div class="flex items-start space-x-4">
                <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}"
                    class="w-20 h-20 object-cover rounded">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-pink-700 dark:text-pink-300">{{ $produk->nama }}</h3>
                    <p class="text-pink-800 dark:text-pink-200 font-bold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    <div class="flex items-center mt-1">
                        <div class="flex text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $produk->rating)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">({{ $produk->total_reviews }} reviews)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Review Form -->
        @php
            $userReview = $produk->reviews->where('user_id', auth()->id())->first();

            // Check if user can review this product (has purchased and received it)
            $canReview = \App\Models\Transaction::where('user_id', auth()->id())
                ->where('status', 'selesai')
                ->whereHas('items', function($query) use ($produk) {
                    $query->where('produk_id', $produk->id);
                })
                ->exists();
        @endphp

        <div class="bg-white dark:bg-gray-700 rounded-lg p-6 mb-6 shadow">
            <h3 class="text-lg font-semibold text-pink-700 dark:text-pink-300 mb-4">
                {{ $userReview ? 'Edit Review Anda' : 'Tulis Review' }}
            </h3>

            @if(!$canReview && !$userReview)
                <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <div class="text-yellow-600 dark:text-yellow-400 text-lg mr-2">⚠️</div>
                        <p class="text-yellow-800 dark:text-yellow-200">
                            Anda hanya dapat memberikan review setelah menerima produk yang telah dibeli.
                        </p>
                    </div>
                </div>
            @endif

            <form action="{{ $userReview ? route('user.reviews.update', $produk->id) : route('user.reviews.store', $produk->id) }}"
                method="POST">
                @csrf
                @if($userReview)
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-2">Rating</label>
                    <div class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                {{ ($userReview && $userReview->rating == $i) ? 'checked' : (!$userReview && $i == 5 ? 'checked' : '') }}
                                class="hidden">
                            <label for="star{{ $i }}" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400 star-rating">
                                ★
                            </label>
                        @endfor
                    </div>
                </div>

                <div class="mb-4">
                    <label for="review" class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-2">Komentar</label>
                    <textarea name="review" id="review" rows="4"
                        class="w-full border border-pink-300 dark:border-gray-600 rounded p-3 focus:outline-pink-400 dark:bg-gray-600 dark:text-white"
                        placeholder="Bagikan pengalaman Anda dengan produk ini..." required>{{ $userReview ? $userReview->review : '' }}</textarea>
                </div>

                <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-600 transition-colors duration-200">
                    {{ $userReview ? 'Update Review' : 'Kirim Review' }}
                </button>
            </form>
        </div>

        <!-- Reviews List -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-pink-700 dark:text-pink-300">Review dari Pembeli Lain</h3>

            @forelse($produk->reviews as $review)
                <div class="bg-white dark:bg-gray-700 rounded-lg p-4 shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="font-semibold text-pink-700 dark:text-pink-300">{{ $review->user->name }}</span>
                                    <div class="flex text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">{{ $review->created_at->format('d M Y') }}</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $review->review }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-700 rounded-lg p-8 text-center shadow">
                    <div class="text-4xl mb-2">📝</div>
                    <p class="text-pink-600 dark:text-pink-400">Belum ada review untuk produk ini.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Jadilah yang pertama memberikan review!</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Star rating interaction
        document.querySelectorAll('.star-rating').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.previousElementSibling.value;
                document.querySelectorAll('.star-rating').forEach(s => {
                    const starRating = parseInt(s.previousElementSibling.value);
                    if (starRating <= rating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });

        // Set initial star display
        const initialRating = document.querySelector('input[name="rating"]:checked');
        if (initialRating) {
            const rating = initialRating.value;
            document.querySelectorAll('.star-rating').forEach(s => {
                const starRating = parseInt(s.previousElementSibling.value);
                if (starRating <= rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                }
            });
        }
    </script>
@endpush
