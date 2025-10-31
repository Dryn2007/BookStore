<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = ['kategori_id', 'nama', 'harga', 'deskripsi', 'foto', 'stock', 'rating', 'total_reviews'];

    public function canBeDeleted()
    {
        return $this->stock <= 0;
    }

    public function hasEnoughStock($quantity)
    {
        return $this->stock >= $quantity;
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
