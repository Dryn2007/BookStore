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
                method="POST" enctype="multipart/form-data">
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

                <div class="mb-4">
                    <label for="photos" class="block text-sm font-medium text-pink-700 dark:text-pink-300 mb-2">Foto (Maksimal 5 foto)</label>
                    <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                        class="w-full border border-pink-300 dark:border-gray-600 rounded p-3 focus:outline-pink-400 dark:bg-gray-600 dark:text-white">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB per foto.</p>
                </div>

                <!-- Photo Preview -->
                <div id="photo-preview" class="mb-4 grid grid-cols-2 md:grid-cols-3 gap-2">
                    @if($userReview && $userReview->photos)
                        @php $existingPhotos = json_decode($userReview->photos, true) ?? [] @endphp
                        @foreach($existingPhotos as $index => $photo)
                            <div class="relative photo-item" data-path="{{ $photo }}">
                                <img src="{{ asset('storage/' . $photo) }}" alt="Preview" class="w-full h-20 object-cover rounded">
                                <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs remove-photo">×</button>
                                <input type="hidden" name="existing_photos[]" value="{{ $photo }}">
                            </div>
                        @endforeach
                    @endif
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
                                <p class="text-gray-800 dark:text-gray-200 mb-3">{{ $review->review }}</p>

                                <!-- Photo Display -->
                                @if($review->photos)
                                    @php $photos = json_decode($review->photos, true) ?? [] @endphp
                                    @if(count($photos) > 0)
                                        <div class="flex space-x-2 mb-2">
                                            @for($i = 0; $i < min(2, count($photos)); $i++)
                                                <img src="{{ asset('storage/' . $photos[$i]) }}" alt="Review photo"
                                                    class="w-16 h-16 object-cover rounded cursor-pointer review-photo"
                                                    data-photos="{{ json_encode($photos) }}"
                                                    data-rating="{{ $review->rating }}"
                                                    data-review="{{ $review->review }}"
                                                    data-user="{{ $review->user->name }}">
                                            @endfor
                                            @if(count($photos) > 2)
                                                <div class="w-16 h-16 bg-gray-300 dark:bg-gray-600 rounded flex items-center justify-center cursor-pointer review-photo relative"
                                                    data-photos="{{ json_encode($photos) }}"
                                                    data-rating="{{ $review->rating }}"
                                                    data-review="{{ $review->review }}"
                                                    data-user="{{ $review->user->name }}">
                                                    <div class="absolute inset-0 bg-gray-300 dark:bg-gray-600 rounded blur-sm"></div>
                                                    <span class="relative text-gray-700 dark:text-gray-300 font-semibold z-10">+{{ count($photos) - 2 }} lagi</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endif
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

        // Photo preview and upload
        document.getElementById('photos').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const preview = document.getElementById('photo-preview');
            const existingCount = preview.querySelectorAll('.photo-item').length;

            if (existingCount + files.length > 5) {
                alert('Maksimal 5 foto diperbolehkan');
                e.target.value = '';
                return;
            }

            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative photo-item';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="w-full h-20 object-cover rounded">
                            <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs remove-photo">×</button>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Remove photo from preview
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-photo')) {
                e.target.closest('.photo-item').remove();
            }
        });

        // Modal for photo gallery
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4';
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

        document.addEventListener('click', function(e) {
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
        document.addEventListener('click', function(e) {
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
