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
                    <label for="rating_min"
                        class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-1">{{ __('messages.rating_min') }}</label>
                    <select name="rating_min" id="rating_min"
                        class="border border-pink-300 dark:border-gray-600 p-2 rounded w-full focus:outline-pink-400 dark:bg-gray-700 dark:text-white">
                        <option value="">{{ __('messages.all_ratings') }}</option>
                        <option value="1" {{ request('rating_min') == '1' ? 'selected' : '' }}>
                            {{ __('messages.stars', ['count' => 1]) }}</option>
                        <option value="2" {{ request('rating_min') == '2' ? 'selected' : '' }}>
                            {{ __('messages.stars', ['count' => 2]) }}</option>
                        <option value="3" {{ request('rating_min') == '3' ? 'selected' : '' }}>
                            {{ __('messages.stars', ['count' => 3]) }}</option>
                        <option value="4" {{ request('rating_min') == '4' ? 'selected' : '' }}>
                            {{ __('messages.stars', ['count' => 4]) }}</option>
                        <option value="5" {{ request('rating_min') == '5' ? 'selected' : '' }}>5
                            {{ __('messages.stars', ['count' => 5]) }}</option>
                    </select>
                </div>

                <div>
                    <label for="sort"
                        class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-1">{{ __('messages.sort_by') }}</label>
                    <select name="sort" id="sort"
                        class="border border-pink-300 dark:border-gray-600 p-2 rounded w-full focus:outline-pink-400 dark:bg-gray-700 dark:text-white">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('messages.latest') }}
                        </option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                            {{ __('messages.cheapest') }}</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                            {{ __('messages.most_expensive') }}</option>
                        <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>
                            {{ __('messages.highest_rating') }}
                        </option>
                        <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>
                            {{ __('messages.best_seller') }}</option>
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
            @forelse ($produk as $item)
                <div class="border border-pink-200 dark:border-gray-600 rounded p-4 shadow bg-white dark:bg-gray-800 flex flex-col justify-between transition-all duration-300 hover:shadow-lg"
                    data-aos="fade-up">
                    <a href="{{ route('user.product.detail', $item->id) }}" class="block">
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}"
                            class="w-full h-40 object-cover rounded mb-2 hover:opacity-90 transition-opacity">
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
                            <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">({{ $item->rating }}/5 -
                                {{ $item->total_reviews }} reviews)</span>
                        </div>

                        <p class="mt-2 text-pink-800 dark:text-pink-200 font-bold">Rp
                            {{ number_format($item->harga, 0, ',', '.') }}</p>
                    </a>

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

    <!-- Reviews Modal - HAPUS ATAU COMMENT OUT -->
    {{-- <div id="reviewsModal" ...>...</div> --}}
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
    </script>
@endpush