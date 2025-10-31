@extends('layouts.navbar')

@section('content')
    <!-- Hero Section Fullscreen -->
    <section class="relative h-screen">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="https://pengadaan.penerbitdeepublish.com/wp-content/uploads/2023/04/ciri-fasilitas-perpustakaan-yang-baik.webp"
                alt="Perpustakaan" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-black/50"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex items-center justify-center h-full text-center">
            <div>
                <h2 class="text-4xl md:text-6xl font-bold text-white drop-shadow-lg">
                    AYO BACA BUKU
                </h2>
            </div>
        </div>
    </section>
@endsection