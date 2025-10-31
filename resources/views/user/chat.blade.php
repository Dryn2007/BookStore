@extends('layouts.user')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded shadow mt-10 p-6">
        <h2 class="text-2xl font-bold mb-4">Chat dengan Admin</h2>

        <div class="h-[400px] overflow-y-auto mb-4 space-y-2">
            @forelse ($messages as $msg)
                <div class="{{ $msg->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                    <div
                        class="inline-block px-4 py-2 rounded-lg {{ $msg->sender_id == auth()->id() ? 'bg-green-100' : 'bg-gray-200' }}">
                        <p class="text-sm">{{ $msg->message }}</p>
                        <p class="text-xs text-gray-500">{{ $msg->created_at->format('H:i') }}</p>
                        @if($msg->sender_id != auth()->id() && !$msg->is_read)
                            <span class="text-xs text-red-500 font-bold">Baru</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Belum ada pesan.</p>
            @endforelse
        </div>

        <form action="{{ route('user.chat.store') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ \App\Models\User::where('role', 'admin')->first()->id }}">
            <input type="text" name="message" class="w-full border rounded p-2" placeholder="Ketik pesan...">
            <button type="submit" class="bg-green-500 text-white px-4 rounded">Kirim</button>
        </form>
      

    </div>
@endsection