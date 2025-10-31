@php
    // Kita gunakan helper 'Str' untuk memotong teks
    use Illuminate\Support\Str;

    // Tentukan panjang teks ringkasan (misal: 300 karakter)
    $characterLimit = 300;
    $shortNarrative = Str::limit($about->narrative, $characterLimit, '...');
    $isTruncated = (strlen($about->narrative) > $characterLimit);
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - {{ $about->store_name }}</title>
    @vite('resources/css/app.css')

    <!-- LANGKAH 1: Tambahkan Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

</head>

<body class="bg-transparent dark:bg-gray-900">

    <div class="container max-w-4xl mx-auto p-6 lg:p-10">

        <!-- Bagian 1: Info Toko & Narasi -->
        <!-- Kita tambahkan x-data="{ open = false }" untuk mengontrol pop-up -->
        <article x-data="{ open: false }" class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden mb-10 relative">

            <!-- Foto Gedung (Tidak berubah) -->
            @if($about->building_image)
                <img class="w-full h-auto max-h-96 object-cover" src="{{ asset('storage/' . $about->building_image) }}"
                    alt="Foto Gedung {{ $about->store_name }}">
            @else
                <div class="w-full h-64 bg-gray-300 flex items-center justify-center text-gray-500">
                    Foto Gedung
                </div>
            @endif

            <!-- Konten Teks -->
            <div class="p-6 md:p-10">
                <!-- Nama Toko (Tidak berubah) -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    {{ $about->store_name }}
                </h1>

                <!-- LANGKAH 2: Narasi yang Diringkas -->
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    <!-- Tampilkan teks yang sudah diringkas -->
                    <p>{{ $shortNarrative }}</p>
                </div>

                <!-- Tampilkan tombol "Read more..." HANYA jika teksnya memang panjang -->
                @if($isTruncated)
                    <button @click="open = true" class="mt-4 text-blue-600 hover:text-blue-800 font-medium">
                        Read more...
                    </button>
                @endif
            </div>

            <!-- LANGKAH 3: Modal (Pop-up) untuk Narasi Penuh -->
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="display: none;">

                <!-- Latar belakang gelap (overlay) - DIGANTI DENGAN INLINE STYLE -->
                <div @click="open = false" class="fixed inset-0" style="background-color: rgba(0, 0, 0, 0.6);"></div>

                <!-- Konten Modal -->
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-auto z-10 overflow-hidden">

                    <!-- Header Modal -->
                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 class="text-xl font-bold text-gray-800">{{ $about->store_name }}</h3>
                        <button @click="open = false"
                            class="text-gray-400 hover:text-gray-600 text-3xl font-bold">&times;</button>
                    </div>

                    <!-- Isi Modal (Narasi Penuh) -->
                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                            <!-- Tampilkan teks narasi yang LENGKAP -->
                            <p>{{ $about->narrative }}</p>
                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="p-4 bg-gray-50 border-t text-right">
                        <button @click="open = false" class="py-2 px-5 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 
                                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

        </article>

        <!-- Bagian 2: Produk Toko Kami (Sesuai Sketsa) -->
        <section class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 md:p-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Produk Toko kami: (Buku Terbaru)
            </h2>

            <!-- Daftar Produk Grid (INI BAGIAN YANG DIGANTI) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">

                <!-- KODE ASLI (dinonaktifkan sementara)
                @forelse($products as $product)
                    <div class="border border-gray-200 rounded-lg p-3 text-center shadow-sm">
                        <div class="font-bold text-lg text-blue-600 mb-2">
                            B{{ $loop->iteration + (($products->currentPage() - 1) * $products->perPage()) }}
                        </div>
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}"
                                class="w-full h-32 object-cover mb-2 rounded">
                        @else
                            <div
                                class="w-full h-32 bg-gray-200 mb-2 rounded flex items-center justify-center text-xs text-gray-500">
                                Gambar Produk
                            </div>
                        @endif
                        <h3 class="text-sm font-medium text-gray-700 truncate">
                            {{ $product->nama ?? 'Nama Produk' }}
                        </h3>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500">
                        Belum ada produk untuk ditampilkan.
                    </p>
                @endforelse
                -->

                <!-- AWAL DUMMY DATA (Hardcoded) -->

                <!-- Dummy Item 1 -->
                <div class="border border-gray-200 rounded-lg p-3 text-center shadow-sm">
                    <div class="font-bold text-lg text-blue-600 mb-2">B1</div>
                    <div
                        class="w-full h-32 bg-gray-200 mb-2 rounded flex items-center justify-center text-xs text-gray-500">
                        Gambar Produk
                    </div>
                    <h3 class="text-sm font-medium text-gray-700 truncate">
                        Buku 1
                    </h3>
                </div>

                <!-- Dummy Item 2 -->
                <div class="border border-gray-200 rounded-lg p-3 text-center shadow-sm">
                    <div class="font-bold text-lg text-blue-600 mb-2">B2</div>
                    <div
                        class="w-full h-32 bg-gray-200 mb-2 rounded flex items-center justify-center text-xs text-gray-500">
                        Gambar Produk
                    </div>
                    <h3 class="text-sm font-medium text-gray-700 truncate">
                        Buku 2
                    </h3>
                </div>

                <!-- Dummy Item 3 -->
                <div class="border border-gray-200 rounded-lg p-3 text-center shadow-sm">
                    <div class="font-bold text-lg text-blue-600 mb-2">B3</div>
                    <div
                        class="w-full h-32 bg-gray-200 mb-2 rounded flex items-center justify-center text-xs text-gray-500">
                        Gambar Produk
                    </div>
                    <h3 class="text-sm font-medium text-gray-700 truncate">
                        Buku 3
                    </h3>
                </div>

                <!-- Dummy Item 4 -->
                <div class="border border-gray-200 rounded-lg p-3 text-center shadow-sm">
                    <div class="font-bold text-lg text-blue-600 mb-2">B4</div>
                    <div
                        class="w-full h-32 bg-gray-200 mb-2 rounded flex items-center justify-center text-xs text-gray-500">
                        Gambar Produk
                    </div>
                    <h3 class="text-sm font-medium text-gray-700 truncate">
                        Buku 4
                    </h3>
                </div>

                <!-- Dummy Item 5 -->
                <div class="border border-gray-200 rounded-lg p-3 text-center shadow-sm">
                    <div class="font-bold text-lg text-blue-600 mb-2">B5</div>
                    <div
                        class="w-full h-32 bg-gray-200 mb-2 rounded flex items-center justify-center text-xs text-gray-500">
                        Gambar Produk
                    </div>
                    <h3 class="text-sm font-medium text-gray-700 truncate">
                        Buku 5
                    </h3>
                </div>
                <!-- AKHIR DUMMY DATA -->

            </div>

            <!-- Pagination (Dinonaktifkan karena data-nya hardcoded) -->
            <!-- 
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            -->

        </section>

    </div>

</body>

</html>