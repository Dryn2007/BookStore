<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $users = User::where('role', 'user')->get();
            $selectedUser = null;
            $messages = collect();
            session()->forget('admin_has_new_message');

            if ($request->has('user_id')) {
                $selectedUser = User::findOrFail($request->user_id);

                // Hapus notif ketika admin buka chat dari user
                session()->forget('has_new_message_from_user_' . $selectedUser->id);

                $messages = Message::where(function ($query) use ($selectedUser) {
                    $query->where('sender_id', Auth::id())
                        ->where('receiver_id', $selectedUser->id);
                })->orWhere(function ($query) use ($selectedUser) {
                    $query->where('sender_id', $selectedUser->id)
                        ->where('receiver_id', Auth::id());
                })
                    ->with('sender', 'receiver')
                    ->orderBy('created_at')
                    ->get();

                // Mark messages as read for admin
                Message::where('sender_id', $selectedUser->id)
                    ->where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }

            return view('admin.chat', compact('users', 'selectedUser', 'messages'));
        } else {
            $admin = User::where('role', 'admin')->first();

            $messages = Message::where(function ($query) use ($user, $admin) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $admin->id);
            })->orWhere(function ($query) use ($user, $admin) {
                $query->where('sender_id', $admin->id)
                    ->where('receiver_id', $user->id);
            })
                ->with('sender', 'receiver')
                ->orderBy('created_at')
                ->get();

            // Hapus notif saat user buka chat
            session()->forget('has_new_message_for_user_' . $user->id);

            // Mark messages as read for user
            Message::where('sender_id', $admin->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return view('user.chat', compact('messages'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        // Set notif untuk penerima
        $receiver = User::find($request->receiver_id);
        if ($receiver && $receiver->role === 'admin') {
            session()->put('has_new_message_from_user_' . Auth::id(), true);
            session()->put('admin_has_new_message', true);
        }

        if ($receiver && $receiver->role === 'user') {
            session()->put('has_new_message_for_user_' . $receiver->id, true);
        }

        return redirect()->back();
    }
}
