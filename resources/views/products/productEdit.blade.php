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
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Most of the existing form remains the same, but add these modifications -->
    
    <!-- For basic fields, add value attributes -->
    <div class="form-group">
        <label for="name">Master Product Name:</label>
        <input type="text" id="name" name="name" class="form-control" 
               value="{{ old('name', $product->name) }}" 
               placeholder="Please Enter" maxlength="300" required 
               style="border-radius: 8px;">
    </div>

    <!-- Similar pattern for other fields -->
    <div class="form-group">
        <label for="spu">SPU:</label>
        <input type="text" id="spu" name="spu" class="form-control" 
               value="{{ old('spu', $product->spu) }}" 
               placeholder="Please Enter" maxlength="200" required 
               style="border-radius: 8px;">
    </div>
    

    <!-- Media Settings Section - Modify to show existing images -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="mb-0">Media Settings</h4>
        </div>
        <div class="card-body">
            <!-- Main Product Images -->
            <div class="mb-4">
                <label class="d-block mb-2">Product Image Max: 9</label>
                <div class="d-flex gap-3 align-items-center flex-wrap">
                    <!-- Placeholder for uploading new images -->
                    <div class="image-upload-box" style="width: 120px; height: 120px;">
                        <input type="file" 
                            class="product-image-input" 
                            name="product_images[]" 
                            accept="image/*" 
                            style="display: none;" 
                            multiple>
                        <div class="upload-placeholder border d-flex align-items-center justify-content-center" 
                            style="width: 100%; height: 100%; cursor: pointer;">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                    
                    <!-- Container for displaying existing and new images -->
                    <div class="product-image-container">
                        <!-- Existing Product Images -->
                        @foreach($product->images as $image)
                            <div class="image-preview position-relative">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image">
                                <span class="remove-image text-danger" data-image-id="{{ $image->id }}">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add a hidden input for tracking removed images -->
    <input type="hidden" name="removed_images[]" id="removed-images" value="">

    <script>
        // Add event listener to remove image buttons
        document.querySelectorAll('.remove-image').forEach(button => {
            button.addEventListener('click', function() {
                const imageId = this.getAttribute('data-image-id');
                const removedImagesInput = document.getElementById('removed-images');
                
                // Add image ID to removed images list
                if (removedImagesInput.value) {
                    removedImagesInput.value += ',' + imageId;
                } else {
                    removedImagesInput.value = imageId;
                }

                // Remove image from DOM
                this.closest('.image-preview').remove();
            });
        });
    </script>
</form>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop