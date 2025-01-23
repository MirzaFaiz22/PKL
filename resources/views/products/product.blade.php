@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row align-items-center" 
        style="border: 1px solid black; border-radius: 15px; padding: 15px; background-color: #f8f9fa; margin-bottom: 5px;">
        <div class="col-md-6">
            <h4 style="margin: 0; color: #343a40; font-weight: bold;">List Product & Service</h4>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('products.create') }}" class="btn btn-primary" style="border-radius: 8px;">Create New</a>
        </div>
    </div>
@stop

@section('content')
    <!-- Filter Section -->
    <div class="row justify-content-end mb-3">
        <div class="col-md-2">
            <input type="text" class="form-control" id="search-input" placeholder="Search...">
        </div>
        <div class="col-md-2">
            <select class="form-control" id="category-filter">
                <option value="">Kategori</option>
                <option value="Ads">Ads</option>
                <option value="Automation">Automation</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" id="type-filter">
                <option value="">Type</option>
                <option value="product">Product</option>
                <option value="service">Service</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-secondary" id="filter-btn">Filter</button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="row">
        <div class="col-md-12" style="border-bottom: 1px solid;">
            <a href="#" id="btn-products" class="tab-link active" onclick="showTable('products')">Products</a>
            <a href="#" id="btn-analysis" class="tab-link" onclick="showTable('analysis')">Product Analysis</a>
            <a href="#" id="btn-services" class="tab-link" onclick="showTable('services')">Services</a>
        </div>
    </div>

    <table id="products-table" class="table table-bordered mt-2" style="border: 1px solid black; border-radius: 15px; overflow: hidden; display: table; background-color: #f8f9fa;">
    <thead class="thead-light">
    <tr>
        <th>No</th>
        <th>Image</th>
        <th>Name</th>
        <th>SPU</th>
        <th>Category</th>
        <th>Sale Status</th>
        <th>Condition</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @foreach ($products as $key => $product)
    <tr data-id="{{ $product->id }}">
        <td>{{ $key + 1 }}</td>
        <td>
            <div class="product-images-container d-flex flex-wrap gap-2">
                @forelse($product->images as $image)
                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                        alt="Product Image {{ $loop->iteration }}" 
                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                @empty
                    <img src="{{ asset('images/no-image.png') }}" 
                        alt="No Image" 
                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                @endforelse
            </div>
        </td>
        <td>{{ $product->name }}</td>
        <td>{{ $product->spu }}</td>
        <td>
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
        </td>
        <td>{{ $product->saleStatus }}</td>
        <td>{{ $product->condition }}</td>
        <td>{{ $product->variations->first()->price ? 'Rp ' . number_format($product->variations->first()->price, 0, ',', '.') : '-' }}</td>
        <td>
            <div class="btn-group">
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form" data-type="product">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
    </table>
    <!-- Product Analysis Table -->
    <table id="analysis-table" class="table table-bordered mt-2" style="border: 1px solid black; border-radius: 15px; overflow: hidden; display: none; background-color: #f8f9fa;">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Stock</th>
                <th>Total Sales</th>
                <th>Min Purchase</th>
                <th>Status</th>
                <th>Competitor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $key => $product)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->variations->sum('stock') }}</td>
                <td>{{ $product->total_sales ?? 0 }}</td>
                <td>{{ $product->minPurchase }}</td>
                <td>{{ $product->saleStatus }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Services Table -->
    <table id="services-table" class="table table-bordered mt-2" style="border: 1px solid black; border-radius: 15px; overflow: hidden; display: none; background-color: #f8f9fa;">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Nama Jasa</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $key => $service)
            <!-- Services Table -->
            <tr data-id="{{ $service->id }}">
                <td>{{ $key + 1 }}</td>
                <td>{{ $service->nama_service }}</td>
                <td>{{ $service->kategori }}</td>
                <td>{{ $service->satuan }}</td>
                <td>Rp {{ number_format($service->harga_beli_service, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($service->harga_jual, 0, ',', '.') }}</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="delete-form" data-type="service">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <style>
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
        text-decoration: none;
    }

    .tab-link.active {
        border-bottom: 3px solid black;
        color: black;
    }
    </style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menampilkan tabel
        function showTable(type) {
            // Sembunyikan semua tabel
            ['products', 'analysis', 'services'].forEach(tableType => {
                const table = document.getElementById(`${tableType}-table`);
                const tabLink = document.getElementById(`btn-${tableType}`);
                
                if (tableType === type) {
                    table.style.display = 'table';
                    tabLink.classList.add('active');
                } else {
                    table.style.display = 'none';
                    tabLink.classList.remove('active');
                }
            });
        }

        // Tambahkan event listener ke tab
        ['btn-products', 'btn-analysis', 'btn-services'].forEach(btnId => {
            const btn = document.getElementById(btnId);
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = btnId.replace('btn-', '');
                    showTable(type);
                });
            }
        });

        // Default tampilkan tabel produk
        showTable('products');

        // Fungsi delete item
        function deleteItem(type, id) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = type === 'product' ? `/products/${id}` : `/services/${id}`;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                location.reload(); // Reload page after delete
            })
            .catch(error => {
                console.error('Error:', error);
                location.reload(); // Reload even on error
            });
        }

        // Tambahkan event listener untuk tombol delete
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const type = this.getAttribute('data-type');
                const id = this.closest('tr').getAttribute('data-id');
                deleteItem(type, id);
            });
        });

        // Fungsionalitas filter
        const filterBtn = document.getElementById('filter-btn');
        if (filterBtn) {
            filterBtn.addEventListener('click', function() {
                const searchText = document.getElementById('search-input').value.toLowerCase();
                const categoryFilter = document.getElementById('category-filter').value.toLowerCase();
                const typeFilter = document.getElementById('type-filter').value.toLowerCase();

                // Filter untuk tabel produk
                document.querySelectorAll('#products-table tbody tr').forEach(row => {
                    const name = row.cells[2].textContent.toLowerCase();
                    const saleStatus = row.cells[4].textContent.toLowerCase();
                    
                    const nameMatch = name.includes(searchText);
                    const categoryMatch = !categoryFilter || saleStatus.includes(categoryFilter);
                    const typeMatch = !typeFilter; // Untuk produk, kita abaikan type filter

                    row.style.display = (nameMatch && categoryMatch && typeMatch) ? '' : 'none';
                });

                // Filter untuk tabel service
                document.querySelectorAll('#services-table tbody tr').forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const category = row.cells[2].textContent.toLowerCase();
                    
                    const nameMatch = name.includes(searchText);
                    const categoryMatch = !categoryFilter || category.includes(categoryFilter);
                    const typeMatch = !typeFilter || typeFilter === 'service';

                    row.style.display = (nameMatch && categoryMatch && typeMatch) ? '' : 'none';
                });
            });
        }
    });
</script>
@stop