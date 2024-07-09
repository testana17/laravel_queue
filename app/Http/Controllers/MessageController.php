<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageSent;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function index()
    {
        $messages = Message::with('user')->get(); // Ambil semua pesan beserta user terkait
        return view('messages.index', compact('messages'));
    }
    
    public function create()
    {
        return view('messages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|exists:users,name', // Validasi nama pengguna harus ada di tabel users
            'content' => 'required|string',
        ]);

        // Cari user berdasarkan nama
        $user = User::where('name', $request->input('username'))->first();

        if (!$user) {
            return redirect()->back()->withErrors(['username' => 'User not found']);
        }

        $message = new Message;
        $message->user_id = $user->id; // Set user_id berdasarkan user yang ditemukan
        $message->content = $request->input('content');
        $message->save();

        // Kirim notifikasi menggunakan Notification
        $user->notify(new MessageSent($message));

        return redirect('/')->with('success', 'Message sent successfully!');
    }
}
