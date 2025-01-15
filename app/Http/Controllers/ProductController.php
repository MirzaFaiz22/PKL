<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Delivery;
use App\Models\Cost;
use App\Models\ProductExtra;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        $products = Product::with(['variants', 'delivery', 'cost', 'extra'])->get();
        return view('products.index', compact('products'));
    }

    // Form untuk menambahkan produk baru
    public function create()
    {
        return view('products.create');
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:300', // Nama produk, kurang dari 300 karakter
            'spu' => 'required|string|unique:products|max:255', // SPU, unik, dan kurang dari 256 karakter
            'fullCategoryId' => 'nullable|array', // ID kategori lengkap (nullable, jika status adalah REVIEW_APPROVED)
            'saleStatus' => 'nullable|string|in:available,out_of_stock,discontinued', // Status penjualan
            'condition' => 'required|string|in:new,used', // Kondisi produk (NEW atau USED)
            'shortDescription' => 'nullable|string|max:3000', // Deskripsi singkat (maks 3000 karakter)
            'description' => 'nullable|string|max:60000', // Deskripsi produk (maks 60000 karakter)
            'variantOptions' => 'nullable|array|max:3', // Opsi varian produk, maksimal 3 varian
            'variations' => 'required|array|max:400', // Variasi produk, maksimal 400
            'images' => 'nullable|array', // URL gambar produk
            'delivery' => 'nullable|array', // Informasi pengiriman
            'type' => 'required|string|in:NORMAL,BUNDLE', // Tipe produk (NORMAL atau BUNDLE)
            'costInfo' => 'nullable|array', // Informasi biaya produk
            'status' => 'required|string|in:PENDING_REVIEW,PAY_NO_ATTENTION', // Status produk utama
            'extraInfo' => 'nullable|array', // Informasi tambahan
            'minPurchase' => 'nullable|integer|min:1|max:1000', // Pembelian minimal (antara 1 dan 1000)
            'brand' => 'nullable|string|max:255', // Merek produk
            'variantOptions.*.name' => 'required|string', // Nama varian seperti warna atau ukuran
            'variantOptions.*.values' => 'required|array|min:1', // Nilai dari varian, misalnya merah, kuning
            'variations.*.optionValues' => 'nullable|array', // Nilai varian, misalnya red code dan L code
            'variations.*.sellingPrice.amount' => 'required|numeric|max:1000000000', // Harga jual produk
            'variations.*.sellingPrice.currencyCode' => 'required|string', // Kode mata uang
            'variations.*.sku' => 'required|string|max:200', // SKU produk
            'variations.*.stock.availableStock' => 'required|integer|min:1|max:999999', // Stok yang tersedia
            'variations.*.purchasePrice.amount' => 'nullable|numeric|max:1000000000', // Harga pembelian produk (opsional)
            'variations.*.barcode' => 'nullable|string|max:128|regex:/^[a-zA-Z0-9-_]+$/', // Barcode produk (opsional, jika ada)
            'delivery.length' => 'nullable|integer|min:1|max:999999', // Panjang produk pengiriman
            'delivery.width' => 'nullable|integer|min:1|max:999999', // Lebar produk pengiriman
            'delivery.height' => 'nullable|integer|min:1|max:999999', // Tinggi produk pengiriman
            'delivery.weight' => 'nullable|integer|max:5000000', // Berat produk pengiriman
            'delivery.lengthUnit' => 'nullable|string|in:cm', // Unit panjang pengiriman
            'delivery.weightUnit' => 'nullable|string|in:g', // Unit berat pengiriman
            'delivery.declareAmount' => 'nullable|numeric', // Jumlah deklarasi produk pengiriman
            'costInfo.sourceUrl' => 'nullable|string|max:500', // URL sumber biaya produk
            'costInfo.purchasingTime' => 'nullable|integer|max:365', // Waktu pembelian produk dalam hari
            'costInfo.purchasingTimeUnit' => 'nullable|string|in:HOUR,DAY,WORK_DAY,WEEK,MONTH', // Unit waktu pembelian
            'extraInfo.preOrder' => 'nullable|array', // Informasi pre-order produk
            'extraInfo.hasShelfLife' => 'nullable|boolean', // Apakah produk memiliki masa simpan
            'extraInfo.shelfLifePeriod' => 'nullable|integer', // Periode masa simpan produk
            'extraInfo.additionInfo' => 'nullable|array', // Informasi tambahan produk
            'extraInfo.remark1' => 'nullable|string|max:50', // Catatan tambahan (remark1)
            'extraInfo.remark2' => 'nullable|string|max:50', // Catatan tambahan (remark2)
            'extraInfo.remark3' => 'nullable|string|max:50', // Catatan tambahan (remark3)
        ]);

        
        // Simpan produk utama
        $product = Product::create($validated);
        
        // Menyimpan relasi tambahan jika ada
        if ($request->has('delivery')) {
            $product->delivery()->create($request->input('delivery'));
        }
        
        if ($request->has('costInfo')) {
            $product->cost()->create($request->input('costInfo'));
        }
        
        if ($request->has('extraInfo')) {
            $product->extra()->create($request->input('extraInfo'));
        }
        
        if ($request->has('variantOptions')) {
            foreach ($request->input('variantOptions') as $variantOption) {
                // Menyimpan informasi varian
            }
        }
        
        if ($request->has('variations')) {
            foreach ($request->input('variations') as $variation) {
                // Menyimpan variasi produk
            }
        }
        
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
        

        // Simpan produk utama
        $product = Product::create($validated);

        // Simpan relasi tambahan (jika ada)
        if ($request->has('delivery')) {
            $product->delivery()->create($request->input('delivery'));
        }

        if ($request->has('cost')) {
            $product->cost()->create($request->input('cost'));
        }

        if ($request->has('extra')) {
            $product->extra()->create($request->input('extra'));
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Menampilkan detail produk
    public function show(Product $product)
    {
        $product->load(['variants', 'delivery', 'cost', 'extra']);
        return view('products.show', compact('product'));
    }

    // Form untuk mengedit produk
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Memperbarui produk
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'status' => 'required|string',
            // Validasi lainnya sesuai kebutuhan
        ]);

        $product->update($validated);

        // Perbarui relasi tambahan
        if ($request->has('delivery')) {
            $product->delivery()->updateOrCreate([], $request->input('delivery'));
        }

        if ($request->has('cost')) {
            $product->cost()->updateOrCreate([], $request->input('cost'));
        }

        if ($request->has('extra')) {
            $product->extra()->updateOrCreate([], $request->input('extra'));
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // Menghapus produk
    public function destroy(Product $product)
    {
        $product->variants()->delete(); // Hapus semua variant
        $product->delivery()->delete(); // Hapus informasi pengiriman
        $product->cost()->delete(); // Hapus informasi biaya
        $product->extra()->delete(); // Hapus informasi tambahan
        $product->delete(); // Hapus produk utama

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
