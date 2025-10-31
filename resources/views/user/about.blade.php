@extends('layouts.user')

@section('content')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: 'Tentang Kami 📚',
                text: 'Website ini dibuat sebagai media pembelajaran dan latihan untuk USK!',
                icon: 'info',
                confirmButtonColor: '#14532d',
                confirmButtonText: 'Lanjutkan'
            });
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-in-out forwards;
        }
    </style>

    <div class="max-w-5xl mx-auto bg-[#14532d] bg-opacity-70 rounded-xl p-8 shadow-lg fade-in">
        <h1 class="text-4xl font-bold mb-6 text-center text-green-200">Tentang Website Ini</h1>
        <p class="text-lg text-white mb-6 leading-relaxed">
            Ini adalah website perpustakaan digital yang saya buat untuk <strong>pembelajaran</strong> dan <strong>persiapan
                Ujian Sekolah (USK)</strong>.
            Website ini meniru sistem profesional, dengan fitur peminjaman buku oleh user dan pengelolaan buku oleh admin.
        </p>

        <h2 class="text-3xl font-semibold text-green-100 mb-4">Tentang Saya</h2>
        <p class="text-white mb-4 leading-relaxed">
            Saya adalah utusan Tuhan untuk <em>meluruskan hidup saya sendiri</em>. Hobi saya cukup unik: menyantap
            <strong>ikan cupang bakar</strong> dan <strong>gulai badak</strong>.
            Saya menyukai Hindia, menjadikan Tuhan sebagai kompas hidup, dan tinggal bersama mama saya (istri dari ayah saya
            😄).
        </p>
        <p class="text-white mb-4 leading-relaxed">
            Cita-cita saya? Masuk surga dulu... lalu ke neraka untuk menjual <strong>es Milo</strong>, karena Milo sangat
            lezat dan semua orang wajib mencobanya! 🧊🍫
        </p>
        <p class="text-white leading-relaxed">
            Oh, dan... <strong>Michie Alexandra</strong> adalah istri saya di kehidupan sebelumnya. Sayangnya, hanya saya
            yang masih ingat. Saya berharap suatu hari bisa bertemu seseorang yang mirip dengannya — mungkin pasangan saya,
            anak saya, atau adik teman saya 😄.
        </p>

        <div class="mt-10 text-center">
            <a href="{{ route('user.dashboard') }}"
                class="inline-block px-6 py-3 bg-green-300 text-green-900 font-bold rounded-full hover:bg-green-400 transition">
                ⬅️ Kembali ke Beranda
            </a>

        </div>
    </div>
@endsection