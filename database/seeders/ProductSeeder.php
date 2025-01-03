<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan 10 produk statis ke dalam tabel produk
        DB::table('produk')->insert([
        ['nama' => 'Produk K', 'kategori' => 'Ads', 'type' => 'Jasa', 'harga' => 6000.00, 'stok' => 100, 'review' => 10, 'terjual' => 5, 'competitor' => 'Competitor 1'],
        ['nama' => 'Produk L', 'kategori' => 'Automation', 'type' => 'Product', 'harga' => 6500.00, 'stok' => 200, 'review' => 20, 'terjual' => 10, 'competitor' => 'Competitor 2'],
        ['nama' => 'Produk M', 'kategori' => 'Ads', 'type' => 'Jasa', 'harga' => 7000.00, 'stok' => 300, 'review' => 30, 'terjual' => 15, 'competitor' => 'Competitor 3'],
        ['nama' => 'Produk N', 'kategori' => 'Automation', 'type' => 'Product', 'harga' => 7500.00, 'stok' => 400, 'review' => 40, 'terjual' => 20, 'competitor' => 'Competitor 4'],
        ['nama' => 'Produk O', 'kategori' => 'Ads', 'type' => 'Jasa', 'harga' => 8000.00, 'stok' => 500, 'review' => 50, 'terjual' => 25, 'competitor' => 'Competitor 5'],
        ['nama' => 'Produk P', 'kategori' => 'Automation', 'type' => 'Product', 'harga' => 8500.00, 'stok' => 600, 'review' => 60, 'terjual' => 30, 'competitor' => 'Competitor 6'],
        ['nama' => 'Produk Q', 'kategori' => 'Ads', 'type' => 'Jasa', 'harga' => 9000.00, 'stok' => 700, 'review' => 70, 'terjual' => 35, 'competitor' => 'Competitor 7'],
        ['nama' => 'Produk R', 'kategori' => 'Automation', 'type' => 'Product', 'harga' => 9500.00, 'stok' => 800, 'review' => 80, 'terjual' => 40, 'competitor' => 'Competitor 8'],
        ['nama' => 'Produk S', 'kategori' => 'Ads', 'type' => 'Jasa', 'harga' => 10000.00, 'stok' => 900, 'review' => 90, 'terjual' => 45, 'competitor' => 'Competitor 9'],
        ['nama' => 'Produk T', 'kategori' => 'Automation', 'type' => 'Product', 'harga' => 10500.00, 'stok' => 1000, 'review' => 100, 'terjual' => 50, 'competitor' => 'Competitor 10'],
        ['nama' => 'Produk U', 'kategori' => 'Ads', 'type' => 'Jasa', 'harga' => 11000.00, 'stok' => 1100, 'review' => 110, 'terjual' => 55, 'competitor' => 'Competitor 11'],
        ['nama' => 'Produk V', 'kategori' => 'Automation', 'type' => 'Product', 'harga' => 11500.00, 'stok' => 1200, 'review' => 120, 'terjual' => 60, 'competitor' => 'Competitor 12'],
        ['nama' => 'Produk W', 'kategori' => 'Ads', 'type' => 'Jasa', 'harga' => 12000.00, 'stok' => 1300, 'review' => 130, 'terjual' => 65, 'competitor' => 'Competitor 13'],
        ['nama' => 'Produk X', 'kategori' => 'Automation', 'type' => 'Product', 'harga' => 12500.00, 'stok' => 1400, 'review' => 140, 'terjual' => 70, 'competitor' => 'Competitor 14'],
        ['nama' => 'Produk Y', 'kategori' => 'Ads', 'type' => 'Jasa', 'harga' => 13000.00, 'stok' => 1500, 'review' => 150, 'terjual' => 75, 'competitor' => 'Competitor 15']
        ]);
    }
}
