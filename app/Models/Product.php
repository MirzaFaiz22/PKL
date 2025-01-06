<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari default (misalnya, 'produk' bukan 'products')
    protected $table = 'produk';

    protected $fillable = [
        'nama',
        'kategori',
        'type',
        'harga',
        'stok',
        'review',
        'terjual',
        'competitor'
    ];
}

