@extends('layouts.admin') {{-- Ganti jika kamu pakai layout lain --}}

@section('content')
    <div class="max-w-6xl mx-auto mt-10 px-4">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
            <i data-lucide="users" class="w-7 h-7"></i> Daftar Akun User
        </h1>

        <p class="mb-6 text-gray-600 dark:text-gray-400">
            Total akun:
            <span
                class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-200 rounded-full text-sm font-semibold">
                {{ $total }}
            </span>
        </p>

        <div class="overflow-hidden rounded-xl shadow">
            <table class="w-full border-collapse bg-white dark:bg-gray-800">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="py-3 px-4 text-left text-gray-700 dark:text-gray-200 font-semibold">#</th>
                        <th class="py-3 px-4 text-left text-gray-700 dark:text-gray-200 font-semibold">Nama</th>
                        <th class="py-3 px-4 text-left text-gray-700 dark:text-gray-200 font-semibold">Email</th>
                        <th class="py-3 px-4 text-left text-gray-700 dark:text-gray-200 font-semibold">Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr
                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">
                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </td>
                            <td class="py-3 px-4 text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-300">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>

        <!-- Back Button -->
        <a href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali ke Dashboard
        </a>
    </div>

    <script>
        lucide.createIcons();
    </script>
@endsection