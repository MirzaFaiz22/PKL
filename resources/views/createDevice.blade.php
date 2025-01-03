@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row align-items-center" 
        style="border: 1px solid black; border-radius: 15px; padding: 15px; background-color: #f8f9fa; ">
        <div class="col-md-6">
            <h4 style="margin: 0; color: #343a40; font-weight: bold;">Create Product</h4>
        </div>
    </div>
@stop

@section('content')
    <div class="container mt-4" style="border: 1px solid black; border-radius: 15px; padding: 40px; background-color: #f8f9fa; ">


        <!-- Pilihan Barang atau Jasa -->
        <div class="form-group">
            <label for="type">Pilih Tipe:</label>
            <select id="type" class="form-control" style="border-radius: 8px;">
                <option value="product" selected>Produk</option>
                <option value="service">Jasa</option>
            </select>
        </div>

        <!-- Form Tambah Produk -->
        <form id="product-form" style="display: none;">
            <div class="form-group">
                <label for="no-product">No Produk:</label>
                <input type="text" id="no-product" class="form-control" readonly style="border-radius: 8px;">
            </div>

            <!-- Gambar Produk -->
            <div class="form-group">
                <label for="gambar-product">Gambar Produk:</label>
                <input type="file" id="gambar-product" class="form-control" style="border-radius: 8px;">
            </div>

            <!-- Nama Produk -->
            <div class="form-group">
                <label for="nama-product">Nama Produk:</label>
                <input type="text" id="nama-product" class="form-control" placeholder="Masukkan Nama Produk" required style="border-radius: 8px;">
            </div>

            <!-- Gambar Varian -->
            <div class="form-group">
                <label for="gambar-varian">Gambar Varian:</label>
                <input type="file" id="gambar-varian" class="form-control" style="border-radius: 8px;">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Nama Varian -->
                    <div class="form-group">
                        <label for="nama-varian">Nama Varian:</label>
                        <input type="text" id="nama-varian" class="form-control" placeholder="Masukkan Nama Varian" required style="border-radius: 8px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Stok Tersedia -->
                    <div class="form-group">
                        <label for="stok-tersedia">Stok Tersedia:</label>
                        <input type="number" id="stok-tersedia" class="form-control" placeholder="0" required style="border-radius: 8px;">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- SKU -->
                    <div class="form-group">
                        <label for="sku">SKU:</label>
                        <input type="text" id="sku" class="form-control" placeholder="Masukkan SKU" required style="border-radius: 8px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Master SKU -->
                    <div class="form-group">
                        <label for="master-sku">Master SKU:</label>
                        <input type="text" id="master-sku" class="form-control" placeholder="Masukkan Master SKU" required style="border-radius: 8px;">
                    </div>
                </div>
            </div>

            <!-- Deskripsi Produk -->
            <div class="form-group">
                <label for="deskripsi-product">Deskripsi Produk:</label>
                <textarea id="deskripsi-product" class="form-control" placeholder="Masukkan Deskripsi Produk" required style="border-radius: 8px;"></textarea>
            </div>

           

            <!-- Button Simpan -->
            <button type="submit" class="btn btn-primary mt-3" style="border-radius: 8px; width: 100%;">Simpan</button>
        </form>

        <!-- Form Tambah Jasa -->
        <form id="service-form" style="display: none;">
            <div class="form-group">
                <label for="no-service">No Jasa:</label>
                <input type="text" id="no-service" class="form-control" readonly style="border-radius: 8px;">
            </div>

            <!-- Nama Jasa & Harga Beli -->
            <div class="row">
                <div class="col-md-6">
                    <label for="nama-service">Nama Jasa:</label>
                    <input type="text" id="nama-service" class="form-control" placeholder="Masukkan Nama Jasa" required style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                    <label for="harga-beli-service">Harga Beli (Rp):</label>
                    <input type="number" id="harga-beli-service" class="form-control" placeholder="0" required style="border-radius: 8px;">
                </div>
            </div>

            <!-- Form Tambah Kategori & Satuan Perhitungan -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="kategori">Kategori:</label>
                    <input type="text" id="kategori" class="form-control" placeholder="Masukkan Kategori" required style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                    <label for="satuan">Satuan Perhitungan:</label>
                    <input type="text" id="satuan" class="form-control" placeholder="Masukkan Satuan Perhitungan" required style="border-radius: 8px;">
                </div>
            </div>

            <!-- Harga Jual -->
            <div class="form-group mt-3">
                <label for="harga-jual">Harga Jual (Rp):</label>
                <input type="number" id="harga-jual" class="form-control" placeholder="0" required style="border-radius: 8px;">
            </div>

            <!-- Button Simpan -->
            <button type="submit" class="btn btn-primary mt-3" style="border-radius: 8px; width: 100%;">Simpan</button>
        </form>

        </div>

        <script>
        let currentProductNumber = 1;
        let currentServiceNumber = 1;

        function updateNoProduct() {
            document.getElementById('no-product').value = `PRD-${currentProductNumber.toString().padStart(4, '0')}`;
        }

        function updateNoService() {
            document.getElementById('no-service').value = `SVC-${currentServiceNumber.toString().padStart(4, '0')}`;
        }

        // Initialize form
        updateNoProduct();
        updateNoService();

        // Handle product form submission
        document.getElementById('product-form').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Produk berhasil disimpan!');
            this.reset();
            currentProductNumber++;
            updateNoProduct();
        });

        // Handle service form submission
        document.getElementById('service-form').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Jasa berhasil disimpan!');
            this.reset();
            currentServiceNumber++;
            updateNoService();
        });

        // Toggle forms based on type selection
        document.getElementById('type').addEventListener('change', function () {
            if (this.value === 'product') {
                document.getElementById('product-form').style.display = 'block';
                document.getElementById('service-form').style.display = 'none';
            } else if (this.value === 'service') {
                document.getElementById('product-form').style.display = 'none';
                document.getElementById('service-form').style.display = 'block';
            }
        });

        // Set default form visibility
        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>


@stop
