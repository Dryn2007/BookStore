<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    use HasFactory;

    // Hanya akan ada 1 baris di tabel ini
    protected $table = 'about_pages';

    protected $fillable = [
        'store_name',
        'building_image',
        'narrative',
    ];
}
