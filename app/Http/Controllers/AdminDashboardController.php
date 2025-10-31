<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah user yang punya pesan baru
        $usersWithNewMessages = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->distinct('sender_id')
            ->pluck('sender_id')
            ->toArray();

        $unreadUsersCount = count($usersWithNewMessages);

        // Set session untuk jumlah user dengan pesan baru
        if ($unreadUsersCount > 0) {
            session()->put('admin_unread_users_count', $unreadUsersCount);
        } else {
            session()->forget('admin_unread_users_count');
        }

        // Set session untuk setiap user yang punya pesan baru
        $users = User::where('role', 'user')->get();
        foreach ($users as $user) {
            if (in_array($user->id, $usersWithNewMessages)) {
                session()->put('has_new_message_from_user_' . $user->id, true);
            } else {
                session()->forget('has_new_message_from_user_' . $user->id);
            }
        }

        return view('admin.dashboard');
    }
}
