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
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                                        {{ $review->review }}
                                    </p>

                                    <!-- Photo Display -->
                                    @if($review->photos)
                                        @php $photos = json_decode($review->photos, true) ?? [] @endphp
                                        @if(count($photos) > 0)
                                            <div class="flex space-x-2">
                                                @for($i = 0; $i < min(2, count($photos)); $i++)
                                                    <img src="{{ asset('storage/' . $photos[$i]) }}" alt="Review photo"
                                                        class="w-12 h-12 object-cover rounded cursor-pointer review-photo"
                                                        data-photos="{{ json_encode($photos) }}" data-rating="{{ $review->rating }}"
                                                        data-review="{{ $review->review }}" data-user="{{ $review->user->name }}">
                                                @endfor
                                                @if(count($photos) > 2)
                                                    <div class="w-12 h-12 bg-gray-300 dark:bg-gray-600 rounded flex items-center justify-center cursor-pointer review-photo relative"
                                                        data-photos="{{ json_encode($photos) }}" data-rating="{{ $review->rating }}"
                                                        data-review="{{ $review->review }}" data-user="{{ $review->user->name }}">
                                                        <div class="absolute inset-0 bg-gray-300 dark:bg-gray-600 rounded blur-sm"></div>
                                                        <span
                                                            class="relative text-gray-700 dark:text-gray-300 font-semibold text-xs z-10">+{{ count($photos) - 2 }}
                                                            lagi</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg ">
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

        // Modal for photo gallery
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-transparent backdrop-brightness-50 hidden z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
                        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-5xl w-full max-h-[90vh] overflow-hidden relative">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-pink-700 dark:text-pink-300" id="modal-title"></h3>
                                <button class="text-gray-500 hover:text-gray-700 text-2xl" id="close-modal">&times;</button>
                            </div>
                            <div class="p-6">
                                <div class="flex flex-col lg:flex-row gap-6">
                                    <div class="lg:w-1/3">
                                        <div class="flex text-yellow-400 text-2xl mb-3" id="modal-rating"></div>
                                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed" id="modal-review"></p>
                                    </div>
                                    <div class="lg:w-2/3 relative">
                                        <div class="relative overflow-hidden rounded-lg bg-black">
                                            <div id="photo-slider" class="flex transition-transform duration-300 ease-in-out">
                                                <!-- Photos will be inserted here -->
                                            </div>
                                            <button id="prev-btn" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all">
                                                ‹
                                            </button>
                                            <button id="next-btn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all">
                                                ›
                                            </button>
                                        </div>
                                        <div class="flex justify-center mt-4 space-x-2" id="photo-indicators">
                                            <!-- Indicators will be inserted here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
        document.body.appendChild(modal);

        // Show modal on photo click
        let currentPhotoIndex = 0;
        let currentPhotos = [];

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('review-photo') || e.target.closest('.review-photo')) {
                const target = e.target.classList.contains('review-photo') ? e.target : e.target.closest('.review-photo');
                currentPhotos = JSON.parse(target.dataset.photos);
                const rating = parseInt(target.dataset.rating);
                const review = target.dataset.review;
                const user = target.dataset.user;
                currentPhotoIndex = 0;

                document.getElementById('modal-title').textContent = `Review oleh ${user}`;
                document.getElementById('modal-review').textContent = review;

                const ratingDiv = document.getElementById('modal-rating');
                ratingDiv.innerHTML = '';
                for (let i = 1; i <= 5; i++) {
                    ratingDiv.innerHTML += i <= rating ? '★' : '☆';
                }

                updatePhotoSlider();
                updateIndicators();

                modal.classList.remove('hidden');
            }
        });

        function updatePhotoSlider() {
            const slider = document.getElementById('photo-slider');
            slider.innerHTML = '';
            currentPhotos.forEach((photo, index) => {
                const img = document.createElement('img');
                img.src = `${window.location.origin}/storage/${photo}`;
                img.alt = `Review photo ${index + 1}`;
                img.className = 'w-full h-96 object-contain flex-shrink-0';
                slider.appendChild(img);
            });
            slider.style.transform = `translateX(-${currentPhotoIndex * 100}%)`;
        }

        function updateIndicators() {
            const indicators = document.getElementById('photo-indicators');
            indicators.innerHTML = '';
            currentPhotos.forEach((_, index) => {
                const indicator = document.createElement('button');
                indicator.className = `w-3 h-3 rounded-full transition-all ${index === currentPhotoIndex ? 'bg-pink-500' : 'bg-gray-400'}`;
                indicator.addEventListener('click', () => {
                    currentPhotoIndex = index;
                    updatePhotoSlider();
                    updateIndicators();
                });
                indicators.appendChild(indicator);
            });
        }

        // Navigation buttons
        document.addEventListener('click', function (e) {
            if (e.target.id === 'prev-btn') {
                if (currentPhotoIndex > 0) {
                    currentPhotoIndex--;
                    updatePhotoSlider();
                    updateIndicators();
                }
            } else if (e.target.id === 'next-btn') {
                if (currentPhotoIndex < currentPhotos.length - 1) {
                    currentPhotoIndex++;
                    updatePhotoSlider();
                    updateIndicators();
                }
            }
        });

        // Close modal
        document.getElementById('close-modal').addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
@endpush