@extends('layouts.user')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-3xl font-bold text-BLACK mb-8 text-center">📦 Riwayat Transaksi</h2>

        @forelse($transaksi as $trx)
            <div class="bg-white rounded-lg p-6 mb-6 shadow-md text-gray-800 border-l-4
                            @if($trx->status === 'pending') border-yellow-500
                            @elseif($trx->status === 'dikirim') border-blue-500
                            @elseif($trx->status === 'selesai') border-green-500
                            @else border-gray-300 @endif">

                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-lg font-semibold">🧾 <span class="text-gray-700">Invoice:</span> #{{ $trx->id }}</p>
                        <p class="mt-1">
                            <span class="font-semibold text-gray-600">Status:</span>
                            <span class="inline-block px-3 py-1 text-sm rounded-full
                                            @if($trx->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($trx->status === 'dikirim') bg-blue-100 text-blue-800
                                            @elseif($trx->status === 'selesai') bg-green-100 text-green-800
                                            @else bg-gray-200 text-gray-600 @endif">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="flex gap-3 items-center">
                        {{-- <a href="{{ route('user.struk', $trx->id) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition">
                            📄 Lihat Struk
                        </a> --}}

                        @if ($trx->status === 'dikirim')
                            <form method="POST" action="{{ route('user.transactions.selesai', $trx->id) }}">
                                @csrf
                                <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition">
                                    ✔ Terima
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Product Items -->
                <div class="mt-4 border-t pt-4">
                    <h4 class="font-semibold text-gray-700 mb-3">📚 Produk yang Dibeli:</h4>
                    <div class="space-y-3">
                        @foreach($trx->items as $item)
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ asset('storage/' . $item->produk->foto) }}" alt="{{ $item->produk->nama }}"
                                        class="w-12 h-12 object-cover rounded">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $item->produk->nama }}</p>
                                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × Rp
                                            {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <!-- Review Button for Completed Orders -->
                                @if($trx->status === 'selesai')
                                    <div class="flex items-center space-x-2">
                                        @php
                                            $userReview = $item->produk->reviews->where('user_id', auth()->id())->first();
                                        @endphp

                                        @if($userReview)
                                            <div class="flex items-center space-x-1 text-yellow-500">
                                                <span>⭐ {{ $userReview->rating }}/5</span>
                                                <a href="{{ route('user.reviews.index', $item->produk->id) }}"
                                                    class="text-blue-500 hover:text-blue-700 text-sm">
                                                    Edit Review
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('user.reviews.index', $item->produk->id) }}"
                                                class="bg-pink-500 hover:bg-pink-600 text-white px-3 py-1 rounded text-sm transition">
                                                ⭐ Beri Rating
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <p class="text-white text-center text-lg">🚫 Belum ada transaksi yang tercatat.</p>
        @endforelse
    </div>
@endsection