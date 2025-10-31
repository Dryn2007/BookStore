@extends('layouts.user')

@section('content')
    <div class="bg-white dark:bg-gray-700 shadow p-6 max-w-6xl mx-auto mt-10 rounded-lg transition-colors duration-300">
        <h1 class="text-2xl font-bold text-pink-800 dark:text-pink-200 mb-4">
            {{ __('messages.welcome_user', ['name' => auth()->user()->name]) }}
        </h1>
        <p class="text-pink-600 dark:text-pink-400 mb-6">{{ __('messages.view_products') }}</p>

        <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('user.cart') }}"
                class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition-colors duration-200">
                🛒 {{ __('messages.cart') }}
            </a>
            <a href="{{ route('user.transactions') }}"
                class="bg-pink-400 text-white px-4 py-2 rounded hover:bg-pink-500 transition-colors duration-200">
                📦 {{ __('messages.transactions') }}
            </a>
            <a href="{{ route('user.wishlist') }}"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors duration-200">
                ❤️ {{ __('messages.wishlist') }}
            </a>
            <a href="{{ route('user.chat') }}"
                class="relative bg-pink-300 text-white px-4 py-2 rounded hover:bg-pink-400 transition-colors duration-200">
                <span>💬 {{ __('messages.chat') }}</span>

                @if(session('user_unread_messages_count'))
                    <span class="absolute -top-2 -right-2 flex h-5 w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span
                            class="relative inline-flex rounded-full h-5 w-5 bg-red-500 text-white text-xs items-center justify-center">{{ session('user_unread_messages_count') }}</span>
                    </span>
                @endif
            </a>
        </div>

        <form method="GET" action="{{ route('user.dashboard') }}" class="mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-4 items-end">

                <div class="md:col-span-2 relative">
                    <label for="search"
                        class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-1">{{ __('messages.search_products') }}</label>
                    <input type="text" name="search" id="search" placeholder="{{ __('messages.search_placeholder') }}"
                        value="{{ request('search') }}"
                        class="border border-pink-300 dark:border-gray-600 p-2 rounded w-full focus:outline-pink-400 dark:bg-gray-700 dark:text-white"
                        oninput="autoSuggest(this.value)">
                    <div id="suggestions"
                        class="absolute z-10 bg-white  border border-gray-300 dark:border- rounded mt-1 w-full max-h-40 overflow-y-auto hidden">
                    </div>
                </div>

                <div>
                    <label for="kategori"
                        class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-1">{{ __('messages.all_categories') }}</label>
                    <select name="kategori" id="kategori"
                        class="border border-pink-300 dark:border-gray-600 p-2 rounded w-full focus:outline-pink-400 dark:bg-gray-700 dark:text-white">
                        <option value="">{{ __('messages.all_categories') }}</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="rating_min" class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-1">{{ __('messages.rating_min') }}</label>
                    <select name="rating_min" id="rating_min"
                        class="border border-pink-300 dark:border-gray-600 p-2 rounded w-full focus:outline-pink-400 dark:bg-gray-700 dark:text-white">
                        <option value="">{{ __('messages.all_ratings') }}</option>
                        <option value="1" {{ request('rating_min') == '1' ? 'selected' : '' }}>{{ __('messages.stars', ['count' => 1]) }}</option>
                        <option value="2" {{ request('rating_min') == '2' ? 'selected' : '' }}>{{ __('messages.stars', ['count' => 2]) }}</option>
                        <option value="3" {{ request('rating_min') == '3' ? 'selected' : '' }}>{{ __('messages.stars', ['count' => 3]) }}</option>
                        <option value="4" {{ request('rating_min') == '4' ? 'selected' : '' }}>{{ __('messages.stars', ['count' => 4]) }}</option>
                        <option value="5" {{ request('rating_min') == '5' ? 'selected' : '' }}>5 {{ __('messages.stars', ['count' => 5]) }}</option>
                    </select>
                </div>

                <div>
                    <label for="sort"
                        class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-1">{{ __('messages.sort_by') }}</label>
                    <select name="sort" id="sort"
                        class="border border-pink-300 dark:border-gray-600 p-2 rounded w-full focus:outline-pink-400 dark:bg-gray-700 dark:text-white">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('messages.latest') }}</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('messages.cheapest') }}</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('messages.most_expensive') }}</option>
                        <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>{{ __('messages.highest_rating') }}
                        </option>
                        <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>{{ __('messages.best_seller') }}</option>
                    </select>
                </div>

                <div>
                    <label for="harga_max" class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-1">Harga
                        Max</label>
                    <input type="number" name="harga_max" id="harga_max" placeholder="Rp 1.000.000"
                        value="{{ request('harga_max') }}"
                        class="border border-pink-300 dark:border-gray-600 p-2 rounded w-full focus:outline-pink-400 dark:bg-gray-700 dark:text-white">
                </div>

            </div>
            <div class="mt-4">
                <button type="submit"
                    class="bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700 transition-colors duration-200">
                    {{ __('messages.search_products') }} & {{ __('messages.sort_by') }}
                </button>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <!-- Ubah bagian card produk -->
            @forelse ($produk as $item)
                <div class="border border-pink-200 dark:border-gray-600 rounded p-4 shadow bg-white dark:bg-gray-800 flex flex-col justify-between transition-all duration-300 hover:shadow-lg"
                    data-aos="fade-up">
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}"
                        class="w-full h-40 object-cover rounded mb-2">
                    <h2 class="text-lg font-semibold text-pink-700 dark:text-pink-300">{{ $item->nama }}</h2>

                    <!-- Rating Display -->
                    <div class="flex items-center mt-1">
                        <div class="flex text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $item->rating)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">({{ $item->total_reviews }} reviews)</span>
                    </div>

                    <!-- Recent Comments Preview -->
                    @if($item->total_reviews > 0)
                        <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-700 rounded text-xs">
                            @php
                                $latestReview = $item->reviews->sortByDesc('created_at')->first();
                            @endphp
                            @if($latestReview)
                                <div class="flex items-start space-x-2">
                                    <div
                                        class="w-4 h-4 bg-pink-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($latestReview->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-1 mb-1">
                                            <span
                                                class="font-medium text-gray-800 dark:text-gray-200">{{ $latestReview->user->name }}</span>
                                            <div class="flex text-yellow-400 text-xs">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $latestReview->rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-400 line-clamp-2">
                                            {{ Str::limit($latestReview->review, 80) }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($item->total_reviews > 1)
                                <button onclick="showReviewsModal({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                    class="text-pink-600 dark:text-pink-400 hover:text-pink-800 dark:hover:text-pink-300 text-xs font-medium mt-2 inline-block">
                                    Read more ({{ $item->total_reviews - 1 }} lainnya)
                                </button>
                            @endif
                        </div>
                    @endif

                    <p class="mt-1 text-pink-800 dark:text-pink-200 font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </p>

                    <!-- Tambahkan informasi stok -->
                    <div class="mt-2 flex items-center">
                        <span
                            class="text-sm {{ $item->stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            @if($item->stock > 0)
                                Stok: {{ $item->stock }}
                            @else
                                Stok Habis
                            @endif
                        </span>
                    </div>

                    <!-- Wishlist Button -->
                    @php
                        $isInWishlist = auth()->user()->wishlists()->where('produk_id', $item->id)->exists();
                    @endphp
                    <button onclick="toggleWishlist({{ $item->id }})"
                        class="mt-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-xl"
                        id="wishlist-btn-{{ $item->id }}">
                        {{ $isInWishlist ? '❤️' : '🤍' }}
                    </button>

                    <form action="{{ route('user.cart.add', $item->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit"
                            class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 w-full {{ $item->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }} transition-colors duration-200"
                            {{ $item->stock <= 0 ? 'disabled' : '' }}>
                            {{ $item->stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>
                    </form>
                </div>
            @empty
                <p class="col-span-full text-pink-600 dark:text-pink-400">Produk tidak ditemukan.</p>
            @endforelse

        </div>
        <div class="mt-8">
            {{ $produk->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Reviews Modal -->
    <div id="reviewsModal"
        class="fixed inset-0  bg-transparent backdrop-brightness-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex w justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">Reviews</h3>
                    <button onclick="closeReviewsModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div id="reviewsContent" class="space-y-4">
                    <!-- Reviews will be loaded here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-suggest search
        function autoSuggest(query) {
            const suggestionsDiv = document.getElementById('suggestions');
            if (query.length < 2) {
                suggestionsDiv.classList.add('hidden');
                return;
            }

            fetch(`/user/dashboard/auto-suggest?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer';
                            div.textContent = item.nama;
                            div.onclick = () => {
                                document.getElementById('search').value = item.nama;
                                suggestionsDiv.classList.add('hidden');
                            };
                            suggestionsDiv.appendChild(div);
                        });
                        suggestionsDiv.classList.remove('hidden');
                    } else {
                        suggestionsDiv.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Toggle wishlist
        function toggleWishlist(produkId) {
            fetch(`/user/wishlist/toggle/${produkId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    const btn = document.getElementById(`wishlist-btn-${produkId}`);
                    if (data.inWishlist) {
                        btn.textContent = '❤️';
                    } else {
                        btn.textContent = '🤍';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', function (e) {
            const suggestions = document.getElementById('suggestions');
            const search = document.getElementById('search');
            if (!search.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.classList.add('hidden');
            }
        });

        // Reviews Modal Functions
        function showReviewsModal(produkId, produkName) {
            document.getElementById('modalTitle').textContent = `Reviews untuk ${produkName}`;
            document.getElementById('reviewsModal').classList.remove('hidden');

            // Load reviews via AJAX
            fetch(`/user/reviews/${produkId}`)
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('reviewsContent');
                    content.innerHTML = '';

                    if (data.reviews && data.reviews.length > 0) {
                        data.reviews.forEach(review => {
                            const reviewDiv = document.createElement('div');
                            reviewDiv.className = 'border-b border-gray-200 dark:border-gray-600 pb-4 last:border-b-0';
                            reviewDiv.innerHTML = `
                                                            <div class="flex items-start space-x-3">
                                                                <div class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                                    ${review.user.name.charAt(0).toUpperCase()}
                                                                </div>
                                                                <div class="flex-1">
                                                                    <div class="flex items-center space-x-2 mb-2">
                                                                        <span class="font-medium text-gray-900 dark:text-white">${review.user.name}</span>
                                                                        <div class="flex text-yellow-400">
                                                                            ${Array.from({ length: 5 }, (_, i) =>
                                i < review.rating ? '★' : '☆'
                            ).join('')}
                                                                        </div>
                                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                            ${new Date(review.created_at).toLocaleDateString('id-ID')}
                                                                        </span>
                                                                    </div>
                                                                    <p class="text-gray-700 dark:text-gray-300">${review.review}</p>
                                                                </div>
                                                            </div>
                                                        `;
                            content.appendChild(reviewDiv);
                        });
                    } else {
                        content.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center">Belum ada review untuk produk ini.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading reviews:', error);
                    document.getElementById('reviewsContent').innerHTML =
                        '<p class="text-red-500 text-center">Gagal memuat review. Silakan coba lagi.</p>';
                });
        }

        function closeReviewsModal() {
            document.getElementById('reviewsModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('reviewsModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeReviewsModal();
            }
        });
    </script>
@endpush