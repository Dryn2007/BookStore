<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * Tentukan kolom mana yang boleh diisi
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];

    /**
     * Relasi ke pengirim (Sender)
     * (Dibutuhkan untuk ->with('sender') di controller Anda)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relasi ke penerima (Receiver)
     * (Dibutuhkan untuk ->with('receiver') di controller Anda)
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
