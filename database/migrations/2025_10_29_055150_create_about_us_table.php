<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('Nama Toko Anda'); // Nama Toko
            $table->text('narration'); // Narasi (min 5 kalimat)
            $table->string('building_photo')->nullable(); // Foto Gedung
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};