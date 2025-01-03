<?php

namespace App\Http\Controllers;

use App\Models\Product; // Pastikan menggunakan model yang benar

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua data produk
        $produk = Product::all(); // Mengambil seluruh data produk

        // Kirim data ke view
        return view('product', compact('produk')); // Mengirim variabel $produk ke view
    }
}
