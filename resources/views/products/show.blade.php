@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div class="row align-items-center" style="border: 1px solid #ccc; border-radius: 15px; padding: 20px; background-color: #f8f9fa;">
    <div class="col-md-8">
        <h4 style="margin: 0; color: #343a40; font-weight: bold;">Product</h4>
    </div>
    <div class="col-md-4 text-right">
        <button class="btn btn-primary">Edit</button>
        <button class="btn btn-danger">Hapus</button>
    </div>
</div>
@stop

@section('content')
<div class="card mt-2" style="padding: 20px;">
    <div class="row">
        <!-- Bagian Kiri: Foto Produk -->
        <div class="col-md-4 text-center">
            <div class="product-image-container">
                @if($product->images->isNotEmpty())
                    <img id="currentImage" src="{{ Storage::url($product->images->first()->image_path) }}" 
                         alt="{{ $product->name }}" class="main-image">
                    
                    <!-- Tombol Navigasi -->
                    <div class="slider-controls">
                        <button id="prevBtn" class="btn btn-secondary btn-sm">← Sebelumnya</button>
                        <button id="nextBtn" class="btn btn-secondary btn-sm">Selanjutnya →</button>
                    </div>
                @else
                    <p class="text-center">Tidak ada gambar </p>
                @endif
            </div>
        </div>

        <!-- Bagian Kanan: Informasi Produk -->
        <div class="col-md-8" style="margin-top: 50px;">
            <h3><strong>{{ $product->name }}</strong></h3>
            <h4 style="color: #28a745;">Rp {{ number_format($product->variations->first()->price, 2) }}</h4>
            <p style="color: #6c757d;">
                <strong>Terjual:</strong> 
                {{ $product->sold_count ?? 0 }} 
                @if($product->has_variations)
                    (dari {{ $product->variations->sum('sold_count') }})
                @endif
            </p>
        </div>
    </div>
    <div class="tab-navigation">
    <!-- Tombol Navigasi -->
    <button id="btn-products" class="tab-link active" onclick="showTable('detailproducts')">Detail Produk</button>
    <button id="btn-analysis" class="tab-link" onclick="showTable('analysis')">Analisis Produk</button>
</div>

<!-- Konten Tabel -->
<div id="detailproducts" class="tab-content">
    <table class="table">
        <tbody>
            <tr>
                <td>
                    <p><strong>SPU:</strong> {{ $product->spu }}</p>
                    <p><strong>Sale Status:</strong> {{ $product->saleStatus }}</p>
                    <p><strong>Kategori:</strong> 
                        @if($product->fullCategoryId)
                            @php
                                $categoryIds = is_string($product->fullCategoryId) 
                                    ? json_decode($product->fullCategoryId) 
                                    : $product->fullCategoryId;
                                $lastCategoryId = is_array($categoryIds) ? end($categoryIds) : null;
                                $categoryName = $lastCategoryId 
                                    ? \App\Helpers\CategoryData::findCategoryName($lastCategoryId) 
                                    : 'Unknown Category';
                            @endphp
                            {{ $categoryName }}
                        @else
                            -
                        @endif
                    </p>
                    <p><strong>Brand:</strong> {{ $product->brand}}</p>
                    <p><strong>Jumlah Pembelian Minimum:</strong> {{ $product->minPurchase }}</p>
                    
                    <h2><strong>Deskripsi Produk</strong></h2>
                    <p>{{ $product->description }}</p>

                    @if($product->has_variations)
                    <h2><strong>Variasi Produk</strong></h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Gambar Variant</th>
                                <th>Nama Variasi</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>MSKU</th>
                                <th>Barcode</th>
                                <th>Kombinasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variations as $variation)
                            <tr>
                                <td>
                                    @if($variation->variant_image_path)
                                        <img src="{{ Storage::url($variation->variant_image_path) }}" 
                                            alt="Variant Image" 
                                            style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $variation->name }}</td>
                                <td>{{ number_format($variation->price, 2) }}</td>
                                <td>{{ $variation->stock }}</td>
                                <td>{{ $variation->msku }}</td>
                                <td>{{ $variation->barcode ?? '-' }}</td>
                                <td>
                                    @php
                                        $combinations = is_string($variation->combinations) 
                                            ? json_decode($variation->combinations, true) 
                                            : $variation->combinations;
                                    @endphp
                                    @if(is_array($combinations))
                                        @foreach($combinations as $type => $value)
                                            {{ $type }}: {{ $value }}<br>
                                        @endforeach
                                    @else
                                        {{ $combinations ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>

<style>
    /* Membuat tabel vertikal dengan header di sebelah kiri */
    .table-vertical th,
    .table-vertical td {
       
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-vertical th {
        width: 150px; /* Menentukan lebar kolom header */
    }

    .table-vertical td {
        width: 200px; /* Menentukan lebar kolom data */
    }

    .table-vertical tr {
        height: 50px; /* Menentukan tinggi baris */
    }
</style>

<div id="analysis" class="tab-content" style="display: none;">
    <table class="table table-bordered table-vertical">
        <thead>
            <tr>
                <th>Nama</th>
                <th>{{ $product->name }}</th>
                <th>Logitech</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Harga</td>
                <td>Rp. {{ number_format($product->variations->first()->price, 0) }}</td>
                <td>Rp. 150,000</td> <!-- Harga Kompetitor Dummy -->
            </tr>
            <tr>
                <td>Stock</td>
                <td>{{ $product->variations->sum('stock') }}</td>
                <td>100</td> <!-- Stock Kompetitor Dummy -->
            </tr>
            <tr>
                <td>Review</td>
                <td>{{ $product->reviews_count ?? 0 }}</td>
                <td>50</td> <!-- Review Kompetitor Dummy -->
            </tr>
            <tr>
                <td>Terjual</td>
                <td>{{ $product->sold_count ?? 0 }}</td>
                <td>200</td> <!-- Terjual Kompetitor Dummy -->
            </tr>
            <tr>
                <td>Selisih Harga</td>
                <td style="color: {{ $product->variations->first()->price > 150000 ? 'green' : 'red' }};">
                    Rp. {{ number_format(abs($product->variations->first()->price - 150000), 0) }}
                </td>
                <td>-</td>
            </tr>
            <tr>
                <td>Selisih Review</td>
                <td style="color: {{ $product->reviews_count > 50 ? 'green' : 'red' }};">
                    {{ abs($product->reviews_count - 50) }}
                </td>
                <td>-</td>
            </tr>
            <tr>
                <td>Selisih Terjual</td>
                <td style="color: {{ $product->sold_count > 200 ? 'green' : 'red' }};">
                    {{ abs($product->sold_count - 200) }}
                </td>
                <td>-</td>
            </tr>
            </tr>
        </tbody>
    </table>
</div>



<script>
    function showTable(tableId) {
        // Sembunyikan semua tab
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => tab.style.display = 'none');

        // Tampilkan tab yang sesuai
        document.getElementById(tableId).style.display = 'block';

        // Ubah status tombol
        const links = document.querySelectorAll('.tab-link');
        links.forEach(link => link.classList.remove('active'));
        document.getElementById(`btn-${tableId}`).classList.add('active');
    }
</script>

</div>

@stop

@section('css')
<style>
/* Navigasi Tab */
.tab-navigation {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-bottom: 2px solid #ccc;
    margin-bottom: 20px;
}

.tab-link {
    padding: 15px;
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    background-color: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    color: #343a40;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tab-link.active {
    color: #007bff;
    border-bottom: 3px solid #007bff;
}

.tab-link:hover {
    background-color: #f8f9fa;
    color: #007bff;
}

/* Tabel */
.custom-table {
    border-radius: 15px;
    overflow: hidden;
    background-color: #f8f9fa;
    border: 1px solid #ccc;
    width: 100%;
}

.custom-table th {
    background-color: #343a40;
    color: #fff;
    text-align: center;
    padding: 15px;
}

.custom-table td {
    padding: 15px;
    vertical-align: top;
}

/* Responsivitas */
@media (max-width: 768px) {
    .tab-link {
        font-size: 14px;
        padding: 10px;
    }
}



.product-slider {
    text-align: center;
    position: relative;
}

.main-image {
    max-width: 25%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.slider-controls {
    margin-top: 10px;
    display: flex;
    justify-content: center;
    gap: 10px;
}

.slider-controls button {
    padding: 5px 10px;
    font-size: 14px;
}
</style>
@stop

@section('js')
<script>
    // Data Gambar
    const images = @json($product->images->pluck('image_path')->map(fn($path) => Storage::url($path)));
    let currentIndex = 0;

    const currentImage = document.getElementById('currentImage');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    // Fungsi untuk memperbarui gambar
    function updateImage(index) {
        if (index >= 0 && index < images.length) {
            currentImage.src = images[index];
            currentIndex = index;
        }
    }

    // Event Listener Tombol
    prevBtn.addEventListener('click', () => {
        const newIndex = currentIndex - 1;
        if (newIndex >= 0) {
            updateImage(newIndex);
        }
    });

    nextBtn.addEventListener('click', () => {
        const newIndex = currentIndex + 1;
        if (newIndex < images.length) {
            updateImage(newIndex);
        }
    });
</script>
@stop
