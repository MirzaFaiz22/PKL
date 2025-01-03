@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row align-items-center" 
        style="border: 1px solid black; border-radius: 15px; padding: 15px; background-color: #f8f9fa; margin-bottom: 5px;">
        <div class="col-md-6">
            <h4 style="margin: 0; color: #343a40; font-weight: bold;">List Product</h4>
        </div>
        <div class="col-md-6 text-right">
            <a href="/createDevice" class="btn btn-primary" style="border-radius: 8px;">Create New Devices</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row justify-content-end" >
        <div class="col-md-1">
            <input type="text" class="form-control" id="search-input" placeholder="Search...">
        </div>
        <div class="col-ms-2">
            <select class="form-control" id="ads-filter">
                <option value="">Kategori</option>
                <option value="Ads">Ads</option>
                <option value="Automation">Automation</option>
            </select>
        </div>
        <div class="col-md-1">
            <select class="form-control" id="product-filter">
                <option value="">Type</option>
                <option value="product">Product</option>
                <option value="jasa">Jasa</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-secondary" id="filter-btn">Filter</button>
        </div>
    </div>


    <div>
        <div class="row" style="margin-top: 20px; margin-left: 10px; margin-right: 20px;">
            <div class="col-md-12" style="border-bottom: 1px solid;">
                <a href="#" id="btn-all" class="tab-link" onclick="showTable('all')">Semua Products</a>
                <a href="#" id="btn-analysis" class="tab-link" onclick="showTable('analysis')">Product Analysis</a>
            </div>
        </div>

        <!-- Tabel Semua Produk -->
        <table id="all-products" class="table table-bordered mt-2" style="border: 1px solid black; border-radius: 15px; overflow: hidden; display: block; background-color: #f8f9fa;">
            <thead class="thead-light" style="border-radius: 15px 15px 0 0; ">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Type</th>
                    <th scope="col">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $key => $item)
                <tr data-category="{{ $item->kategori }}" data-type="{{ $item->type }}">
                    <th scope="row">{{ $key + 1 }}</th>
                    <td><a href="/product/{{ $item->id }}">{{ $item->nama }}</a></td>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->harga }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>


        <!-- Tabel Analisis Produk -->
        <table id="product-analysis" class="table table-bordered mt-2" 
            style="border: 1px solid black; border-radius: 15px; overflow: hidden; display: none;">
            <thead class="thead-light" style="border-radius: 15px 15px 0 0; background-color: #f8f9fa;">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Review</th>
                    <th scope="col">Terjual</th>
                    <th scope="col">Competitor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $key => $item)
                <tr data-category="{{ $item->kategori }}" data-type="{{ $item->type }}">
                    <th scope="row">{{ $key + 1 }}</th>
                    <td><a href="/product/{{ $item->id }}">{{ $item->nama }}</a></td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $item->review }}</td>
                    <td>{{ $item->terjual }}</td>
                    <td>{{ $item->competitor }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <style>
        /* Styling untuk tombol */
        .tab-link {
            display: inline-block;
            padding: 10px 20px;
            color: black;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: border-color 0.3s, color 0.3s;
        }

        .tab-link:hover {
            color: gray;
        }

        .tab-link.active {
            border-bottom: 3px solid black;
            color: black;
        }
        </style>

        <script>
        function showTable(type) {
            // Sembunyikan semua tabel
            document.getElementById('all-products').style.display = 'none';
            document.getElementById('product-analysis').style.display = 'none';

            // Tampilkan tabel sesuai dengan tipe
            const tableId = type === 'all' ? 'all-products' : 'product-analysis';
            document.getElementById(tableId).style.display = 'table';

            // Perbarui status aktif pada tombol    
            document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
            document.getElementById(`btn-${type}`).classList.add('active');
        }

        // Default tampilan
        showTable('all');

        document.getElementById("filter-btn").addEventListener("click", function() {
            // Ambil nilai input dan select
            var searchText = document.getElementById("search-input").value.toLowerCase();
            var adsFilter = document.getElementById("ads-filter").value.toLowerCase();
            var productFilter = document.getElementById("product-filter").value.toLowerCase();

            // Ambil semua baris tabel
            var rowsAllProducts = document.querySelectorAll("#all-products tbody tr");
            var rowsProductAnalysis = document.querySelectorAll("#product-analysis tbody tr");

            // Fungsi untuk memfilter baris
            function filterRows(rows) {
                rows.forEach(function(row) {
                    var nameCell = row.cells[1].textContent.toLowerCase();
                    var categoryCell = row.cells[2].textContent.toLowerCase();
                    var typeCell = row.cells[3].textContent.toLowerCase();
                    var adsData = row.getAttribute("data-category").toLowerCase();  // Lowercase untuk perbandingan
                    var productData = row.getAttribute("data-type").toLowerCase();

                    var isNameMatch = nameCell.includes(searchText);
                    var isAdsMatch = adsFilter === "" || adsData === adsFilter;
                    var isProductMatch = productFilter === "" || productData === productFilter;

                    // Jika baris cocok dengan semua filter, tampilkan, jika tidak sembunyikan
                    if (isNameMatch && isAdsMatch && isProductMatch) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }

            // Terapkan filter ke kedua tabel
            filterRows(rowsAllProducts);
            filterRows(rowsProductAnalysis);
        });
        </script>
    </div>
@stop

