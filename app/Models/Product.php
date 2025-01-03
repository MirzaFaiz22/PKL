<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    //
    use HasFactory;
    protected $table = 'produk';
    protected $fillable = ['nama', 'kategori', 'type', 'harga', 'stok', 'review', 'terjual', 'competitor'];
}
