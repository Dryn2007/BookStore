<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // Ini akan menambahkan kolom 'kategori_id'
            $table->foreignId('kategori_id')
                ->after('harga')      // (Opsional: menempatkan kolom setelah 'harga')
                ->nullable()          // <-- PENTING: Dibuat nullable agar data lama Anda tidak error
                ->constrained('kategoris') // Menghubungkan ke tabel 'kategoris'
                ->onDelete('set null');  // Jika kategori dihapus, produk ini menjadi NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // Ini adalah kebalikan dari 'up' untuk rollback
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });
    }
};
