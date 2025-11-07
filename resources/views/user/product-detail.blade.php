@extends('layouts.user')

@section('content')
    <div class="bg-white dark:bg-gray-700 shadow p-6 max-w-6xl mx-auto mt-10 rounded-lg transition-colors duration-300">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('user.dashboard') }}" class="text-pink-600 dark:text-pink-400 hover:underline">
                ← Kembali ke Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div>
                <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}"
                    class="w-full h-96 object-cover rounded-lg shadow-lg">
            </div>

            <!-- Product Info -->
            <div class="flex flex-col">
                <h1 class="text-3xl font-bold text-pink-800 dark:text-pink-200 mb-4">{{ $produk->nama }}</h1>

                <!-- Rating -->
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400 text-2xl">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($produk->reviews_avg_rating))
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-600 dark:text-gray-400 ml-3">
                        {{ number_format($produk->reviews_avg_rating, 1) }}/5 ({{ $produk->reviews_count }} reviews)
                    </span>
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <span class="text-3xl font-bold text-pink-800 dark:text-pink-200">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Stock -->
                <div class="mb-6">
                    <span
                        class="text-lg {{ $produk->stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        @if($produk->stock > 0)
                            Stok: {{ $produk->stock }} tersedia
                        @else
                            Stok Habis
                        @endif
                    </span>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-pink-700 dark:text-pink-300 mb-2">Deskripsi Produk</h3>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $produk->deskripsi ?? 'Tidak ada deskripsi tersedia untuk produk ini.' }}
                    </p>
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <span
                        class="inline-block bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-200 px-3 py-1 rounded-full text-sm">
                        {{ $produk->kategori->nama }}
                    </span>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 mt-auto">
                    <form action="{{ route('user.cart.add', $produk->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full bg-pink-500 text-white px-6 py-3 rounded-lg hover:bg-pink-600 {{ $produk->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }} transition-colors duration-200 font-semibold"
                            {{ $produk->stock <= 0 ? 'disabled' : '' }}>
                            🛒 {{ $produk->stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>
                    </form>

                    <button onclick="toggleWishlist({{ $produk->id }})"
                        class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors duration-200 text-2xl"
                        id="wishlist-btn-{{ $produk->id }}">
                        {{ $isInWishlist ? '❤️' : '🤍' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-pink-800 dark:text-pink-200 mb-6">
                Reviews Pelanggan ({{ $produk->reviews_count }})
            </h2>

            @if($produk->reviews->count() > 0)
                <div class="space-y-6">
                    @foreach($produk->reviews as $review)
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6 last:border-b-0">
                            <div class="flex items-start space-x-4">
                                <div
                                    class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="font-semibold text-gray-900 dark:text-white text-lg">
                                            {{ $review->user->name }}
                                        </span>
                                        <div class="flex text-yellow-400 text-lg">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    ★
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {{ $review->review }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">
                        Belum ada review untuk produk ini. Jadilah yang pertama memberikan review!
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
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
                    btn.textContent = data.inWishlist ? '❤️' : '🤍';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endpush