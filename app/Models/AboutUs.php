<?php
// app/Models/AboutUs.php


namespace App\Models; // Pastikan namespace Anda benar

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'about_us'; // Nama tabel

    protected $fillable = [
        'store_name',
        'narration',
        'building_photo',
    ];
}
