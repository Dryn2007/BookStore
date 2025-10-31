<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Halaman About Us</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">

    <div class="container max-w-4xl mx-auto p-6 lg:p-10">

        <div class="bg-white shadow-xl rounded-lg overflow-hidden p-6 md:p-10">

            <h2 class="text-3xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200">
                Edit Halaman "Tentang Kami"
            </h2>

            <!-- Notifikasi Sukses -->
            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-5" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Notifikasi Error Validasi -->
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-5" role="alert">
                <strong class="font-bold">Oops! Ada kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Field Nama Toko -->
                <div>
                    <label for="store_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                    <input type="text" name="store_name" id="store_name"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                  focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('store_name', $about->store_name) }}" required>
                </div>

                <!-- Field Narasi -->
                <div>
                    <label for="narrative" class="block text-sm font-medium text-gray-700 mb-1">Narasi</label>
                    <textarea name="narrative" id="narrative" rows="8"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                     focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>{{ old('narrative', $about->narrative) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Tulis minimal 5 kalimat.</p>
                </div>

                <!-- Field Foto Gedung -->
                <div>
                    <label for="building_image" class="block text-sm font-medium text-gray-700 mb-1">Upload Foto Gedung</label>
                    <input type="file" name="building_image" id="building_image"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg 
                                  cursor-pointer bg-gray-50 focus:outline-none 
                                  file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 
                                  file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 
                                  hover:file:bg-blue-100">

                    @if($about->building_image)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Gambar Saat Ini:</p>
                        <img src="{{ asset('storage/' . $about->building_image) }}" alt="Foto Gedung"
                            class="mt-2 max-w-xs h-auto rounded-md border border-gray-200 p-1">
                    </div>
                    @endif
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200">

                    <!-- Tombol Kembali (ke dashboard) -->
                    <a href="{{-- route('admin.dashboard') --}}"
                        class="inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm 
                              text-sm font-medium rounded-md text-gray-700 bg-white 
                              hover:bg-gray-50">
                        Kembali
                    </a>

                    <!-- Tombol Lihat Halaman Publik -->
                    <a href="{{ route('about.show') }}" target="_blank"
                        class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm 
                              text-sm font-medium rounded-md text-gray-700 bg-gray-200 
                              hover:bg-gray-300">
                        Lihat Halaman
                    </a>

                    <!-- Tombol Simpan -->
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm 
                                   text-sm font-medium rounded-md text-white bg-blue-600 
                                   hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>

</html>