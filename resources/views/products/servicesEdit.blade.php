@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row align-items-center" 
            style="border: 1px solid #ccc; border-radius: 15px; padding: 20px; background-color: #f8f9fa; ">
            <div class="col-md-6">
                <h4 style="margin: 0; color: #343a40; font-weight: bold;">Edit Jasa</h4>
            </div>
        </div>
@stop

@section('content')
    <div class="container mt-4" style="border: 1px solid #ccc; border-radius: 15px; padding: 40px; background-color: #f8f9fa; ">
        <!-- Form Edit Jasa -->
        <form id="service-form" action="{{ route('services.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="service">
            
            <!-- Nama Jasa & Harga Beli -->
            <div class="row">
                <div class="col-md-6">
                    <label for="nama-service">Nama Jasa:</label>
                    <input type="text" id="nama-service" name="nama_service" class="form-control @error('nama_service') is-invalid @enderror" value="{{ old('nama_service', $service->nama_service) }}" required style="border-radius: 8px;">
                    @error('nama_service')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="harga-beli-service">Harga Beli (Rp):</label>
                    <input type="number" id="harga-beli-service" name="harga_beli_service" class="form-control @error('harga_beli_service') is-invalid @enderror" value="{{ old('harga_beli_service', $service->harga_beli_service) }}" required style="border-radius: 8px;">
                    @error('harga_beli_service')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Kategori & Satuan -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="kategori">Kategori:</label>
                    <input type="text" id="kategori" name="kategori" class="form-control @error('kategori') is-invalid @enderror" value="{{ old('kategori', $service->kategori) }}" required style="border-radius: 8px;">
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="satuan">Satuan Perhitungan:</label>
                    <input type="text" id="satuan" name="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan', $service->satuan) }}" required style="border-radius: 8px;">
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Harga Jual -->
            <div class="form-group mt-3">
                <label for="harga-jual">Harga Jual (Rp):</label>
                <input type="number" id="harga-jual" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual', $service->harga_jual) }}" required style="border-radius: 8px;">
                @error('harga_jual')
                    <div class="invalid-feedback">{{ $message }}</div>  
                @enderror
            </div>
        </form>

        <!-- Tombol -->
        <div class="mt-4">
            <button type="submit" form="service-form" class="btn btn-primary" style="border-radius: 8px;">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary ml-2" style="border-radius: 8px;">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop