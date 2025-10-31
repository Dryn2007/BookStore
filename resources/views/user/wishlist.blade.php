@extends('layouts.user')

@section('content')
    <div class="bg-pink-50 dark:bg-gray-800 shadow p-6 max-w-6xl mx-auto mt-10 rounded-lg transition-colors duration-300">
        <h1 class="text-2xl font-bold text-pink-800 dark:text-pink-200 mb-4">Wishlist Saya</h1>

        @forelse($wishlists as $wishlist)
            @if($loop->first)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @endif

                <div class="border border-pink-200 dark:border-gray-600 rounded p-4 shadow bg-white dark:bg-gray-800 flex flex-col justify-between transition-all duration-300 hover:shadow-lg"
                    data-aos="fade-up">
                    <img src="{{ asset('storage/' . $wishlist->produk->foto) }}" alt="{{ $wishlist->produk->nama }}"
                        class="w-full h-40 object-cover rounded mb-2">
                    <h2 class="text-lg font-semibold text-pink-700 dark:text-pink-300">
                        {{ $wishlist->produk->nama }}
                    </h2>

                    <!-- Rating Display -->
                    <div class="flex items-center mt-1">
                        <div class="flex text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $wishlist->produk->rating)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">({{ $wishlist->produk->total_reviews }}
                            reviews)</span>
                    </div>

                    <!-- Link to Reviews -->
                    @if($wishlist->produk->total_reviews > 0)
                        <a href="{{ route('user.reviews.index', $wishlist->produk->id) }}"
                            class="text-sm text-pink-600 dark:text-pink-400 hover:text-pink-800 dark:hover:text-pink-300 mt-1 inline-block">
                            Lihat {{ $wishlist->produk->total_reviews }} Reviews →
                        </a>
                    @endif

                    <p class="mt-1 text-pink-800 dark:text-pink-200 font-bold">Rp
                        {{ number_format($wishlist->produk->harga, 0, ',', '.') }}
                    </p>

                    <!-- Stok -->
                    <div class="mt-2 flex items-center">
                        <span
                            class="text-sm {{ $wishlist->produk->stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            @if($wishlist->produk->stock > 0)
                                Stok: {{ $wishlist->produk->stock }}
                            @else
                                Stok Habis
                            @endif
                        </span>
                    </div>

                    <!-- Remove from Wishlist -->
                    <form action="{{ route('user.wishlist.remove', $wishlist->produk->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-xl">
                            🗑️ Hapus dari Wishlist
                        </button>
                    </form>

                    <form action="{{ route('user.cart.add', $wishlist->produk->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit"
                            class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 w-full {{ $wishlist->produk->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }} transition-colors duration-200"
                            {{ $wishlist->produk->stock <= 0 ? 'disabled' : '' }}>
                            {{ $wishlist->produk->stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>
                    </form>
                </div>

                @if($loop->last)
                    </div>
                @endif
        @empty
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🤍</div>
                <h2 class="text-xl font-semibold text-pink-700 dark:text-pink-300 mb-2">Wishlist Kosong</h2>
                <p class="text-pink-600 dark:text-pink-400 mb-4">Belum ada produk yang ditambahkan ke wishlist.</p>
                <a href="{{ route('user.dashboard') }}"
                    class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-600 transition-colors duration-200">
                    Jelajahi Produk
                </a>
            </div>
        @endforelse
    </div>
@endsection