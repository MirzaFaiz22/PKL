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

            <!-- Product Information-->
<h4 style="font-weight: bold;">Product Information:</h4>

<div class="form-group d-flex align-items-center mb-4">
    <label for="product-variations" class="mr-2">The product has variations:</label>
    <input type="checkbox" 
        id="product-variations" 
        name="hasVariations" 
        value="1" 
        class="form-control" 
        style="width: auto;"
        {{ $product->has_variations ? 'checked' : '' }}>
</div>

<div id="variation-fields" style="display: {{ $product->has_variations ? 'block' : 'none' }};">
    <!-- First Variation Type -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label for="variation-type-1">First Variation Type:</label>
                <input type="text" 
                    id="variation-type-1" 
                    name="variantTypes[0][name]" 
                    class="form-control" 
                    placeholder="e.g., color"
                    value="{{ $variantTypes[0]['name'] ?? '' }}">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="variation-value-1">Option Value:</label>
                <div class="input-group">
                    <input type="text" 
                        id="variation-value-1" 
                        class="form-control" 
                        placeholder="e.g., Red, Blue">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" onclick="addValue(1)">Add</button>
                    </div>
                </div>
                <div id="values-list-1" class="mt-2 d-flex flex-wrap gap-2"></div>
                <input type="hidden" name="variantTypes[0][values]" id="variation-values-1">
            </div>
        </div>
    </div>

    <!-- Second Variation Type -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label for="variation-type-2">Second Variation Type:</label>
                <input type="text" 
                    id="variation-type-2" 
                    name="variantTypes[1][name]" 
                    class="form-control" 
                    placeholder="e.g., size"
                    value="{{ $variantTypes[1]['name'] ?? '' }}">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="variation-value-2">Option Value:</label>
                <div class="input-group">
                    <input type="text" 
                        id="variation-value-2" 
                        class="form-control" 
                        placeholder="e.g., S, M, L">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" onclick="addValue(2)">Add</button>
                    </div>
                </div>
                <div id="values-list-2" class="mt-2 d-flex flex-wrap gap-2"></div>
                <input type="hidden" name="variantTypes[1][values]" id="variation-values-2">
            </div>
        </div>
    </div>

    <!-- Variations Table -->
    <div class="table-responsive mt-4">
        <table id="variations-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Default Price</th>
                    <th>Available Stock</th>
                    <th>MSKU</th>
                    <th>Barcode</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody id="variations-body">
                <!-- Will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Base Fields -->
<div id="base-fields" style="display: {{ !$product->has_variations ? 'block' : 'none' }}">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Default Price</th>
                    <th>Available Stock</th>
                    <th>MSKU</th>
                    <th>Barcode</th>
                </tr>
            </thead>
            @php
                // Untuk produk baru
                if (!isset($product)) {
                    $baseVariation = null;
                } else {
                    // Ambil variasi dasar (untuk produk tanpa variasi)
                    $baseVariation = $product->variations()->where('name', '-')->first();
                }
            @endphp

            <tbody>
                <tr>
                    <td>-</td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" 
                                name="basePrice" 
                                class="form-control" 
                                value="{{ old('basePrice', $baseVariation->price ?? '') }}"
                                placeholder="Please Enter">
                        </div>
                    </td>
                    <td>
                        <input type="number" 
                            name="baseStock" 
                            class="form-control" 
                            value="{{ old('baseStock', $baseVariation->stock ?? '') }}"
                            placeholder="Should be between 0-999,999">
                    </td>
                    <td>
                        <input type="text" 
                            name="baseMsku" 
                            class="form-control" 
                            value="{{ old('baseMsku', $baseVariation->msku ?? '') }}"
                            placeholder="Please Enter">
                    </td>
                    <td>
                        <input type="text" 
                            name="baseBarcode" 
                            class="form-control" 
                            value="{{ old('baseBarcode', $baseVariation->barcode ?? '') }}"
                            placeholder="Barcode only supports letters, numb...">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Product Variation Management Script for Edit Functionality

// Global configuration and validation constants
const CONFIG = {
    MAX_VARIATION_VALUES: 10,
    MAX_VARIATION_PHOTO_SIZE: 5 * 1024 * 1024, // 5MB
    ALLOWED_IMAGE_TYPES: ['image/jpeg', 'image/png', 'image/webp']
};

 // Inisialisasi variasi nilai dari backend
 let variationValues = {
        1: {!! 
            json_encode(
                isset($variantTypes[0]['values']) && is_array($variantTypes[0]['values']) 
                ? $variantTypes[0]['values'] 
                : []
            ) 
        !!},
        2: {!! 
            json_encode(
                isset($variantTypes[1]['values']) && is_array($variantTypes[1]['values']) 
                ? $variantTypes[1]['values'] 
                : []
            ) 
        !!}
    };

    // Inisialisasi variasi yang sudah ada
    const existingVariations = {!! 
        json_encode(
            $variations->map(function($variation) {
                // Parse kombinasi jika masih dalam string
                $combinations = is_string($variation->combinations) 
                    ? json_decode($variation->combinations, true) 
                    : $variation->combinations;

                return [
                    'id' => $variation->id,
                    'name' => $variation->name,
                    'price' => $variation->price,
                    'stock' => $variation->stock,
                    'msku' => $variation->msku,
                    'barcode' => $variation->barcode,
                    'combinations' => $combinations,
                    'photo' => $variation->variant_image_path 
                        ? asset('storage/' . $variation->variant_image_path)
                        : null
                ];
            }) ?? []
        ) 
    !!};

     // Inisialisasi nama tipe variasi
     document.addEventListener('DOMContentLoaded', function() {
        const variantType1Input = document.getElementById('variation-type-1');
        const variantType2Input = document.getElementById('variation-type-2');

        @if(isset($variantTypes[0]['name']))
            variantType1Input.value = "{{ $variantTypes[0]['name'] }}";
        @endif

        @if(isset($variantTypes[1]['name']))
            variantType2Input.value = "{{ $variantTypes[1]['name'] }}";
        @endif

        // Trigger initial setup
        updateVariationLists();
        generateVariationRows();
        updateHiddenInputs();
    });

// Comprehensive form validation function
function validateProductForm() {
    const hasVariations = document.getElementById('product-variations').checked;
    
    if (hasVariations) {
        // Validate variation types
        const type1 = document.getElementById('variation-type-1').value.trim();
        const type2 = document.getElementById('variation-type-2').value.trim();
        
        // Ensure first type is filled if second type is present
        if (!type1 && type2) {
            alert('Please fill in the first variation type or remove the second one.');
            return false;
        }
        
        // Validate variation values
        if (variationValues[1].length === 0 && type1) {
            alert('Please add at least one value for the first variation type.');
            return false;
        }
        
        if (variationValues[2].length === 0 && type2) {
            alert('Please add at least one value for the second variation type.');
            return false;
        }
        
        // Validate variations table inputs
        const variationsBody = document.getElementById('variations-body');
        const rows = variationsBody.querySelectorAll('tr');
        
        if (rows.length === 0) {
            alert('Please generate variation combinations.');
            return false;
        }
        
        for (let row of rows) {
            const priceInput = row.querySelector('input[name$="[price]"]');
            const stockInput = row.querySelector('input[name$="[stock]"]');
            const mskuInput = row.querySelector('input[name$="[msku]"]');
            const barcodeInput = row.querySelector('input[name$="[barcode]"]');
            
            // Price validation (non-negative)
            const price = parseFloat(priceInput.value);
            if (isNaN(price) || price < 0) {
                alert('Please enter a valid price (0 or greater) for all variations.');
                priceInput.focus();
                return false;
            }
            
            // Stock validation
            const stock = parseInt(stockInput.value);
            if (isNaN(stock) || stock < 0 || stock > 999999) {
                alert('Stock must be between 0 and 999,999 for all variations.');
                stockInput.focus();
                return false;
            }
            
            // MSKU validation
            if (!mskuInput.value.trim()) {
                alert('Please enter an MSKU for all variations.');
                mskuInput.focus();
                return false;
            }
            
            // Barcode validation (alphanumeric)
            const barcodeValue = barcodeInput.value.trim();
            if (!barcodeValue || !/^[a-zA-Z0-9]+$/.test(barcodeValue)) {
                alert('Barcode should only contain letters and numbers.');
                barcodeInput.focus();
                return false;
            }
        }
    } else {
        // Validate base product fields when no variations
        const basePrice = document.querySelector('input[name="basePrice"]');
        const baseStock = document.querySelector('input[name="baseStock"]');
        const baseMsku = document.querySelector('input[name="baseMsku"]');
        const baseBarcode = document.querySelector('input[name="baseBarcode"]');
        
        // Base price validation
        const price = parseFloat(basePrice.value);
        if (isNaN(price) || price < 0) {
            alert('Please enter a valid base price (0 or greater).');
            basePrice.focus();
            return false;
        }
        
        // Base stock validation
        const stock = parseInt(baseStock.value);
        if (isNaN(stock) || stock < 0 || stock > 999999) {
            alert('Base stock must be between 0 and 999,999.');
            baseStock.focus();
            return false;
        }
        
        // Base MSKU validation
        if (!baseMsku.value.trim()) {
            alert('Please enter a base MSKU.');
            baseMsku.focus();
            return false;
        }
        
        // Base barcode validation
        const baseBarcodValue = baseBarcode.value.trim();
        if (!baseBarcodValue || !/^[a-zA-Z0-9]+$/.test(baseBarcodValue)) {
            alert('Base barcode should only contain letters and numbers.');
            baseBarcode.focus();
            return false;
        }
    }
    
    return true;
}

// Function to add variation value
function addValue(typeNumber) {
    const input = document.getElementById(`variation-value-${typeNumber}`);
    const value = input.value.trim();
    const typeInput = document.getElementById(`variation-type-${typeNumber}`);
    const typeName = typeInput.value.trim();

    // Validate type name is entered
    if (!typeName) {
        alert('Please enter variation type first');
        typeInput.focus();
        return;
    }

    // Check maximum variation values
    if (variationValues[typeNumber].length >= CONFIG.MAX_VARIATION_VALUES) {
        alert(`Maximum ${CONFIG.MAX_VARIATION_VALUES} variation values allowed`);
        return;
    }

    // Validate value
    if (!value) {
        alert('Please enter a variation value');
        input.focus();
        return;
    }

    // Check for duplicates
    if (variationValues[typeNumber].includes(value)) {
        alert('This variation value already exists');
        input.value = '';
        return;
    }

    // Validate value format
    if (!/^[a-zA-Z0-9 ]+$/.test(value)) {
        alert('Variation values should only contain letters, numbers, and spaces');
        return;
    }

    // Add value
    variationValues[typeNumber].push(value);
    input.value = '';
    updateVariationLists();
    generateVariationRows();
    updateHiddenInputs();
}

// Remove variation value
function removeValue(typeNumber, value) {
    const index = variationValues[typeNumber].indexOf(value);
    if (index > -1) {
        variationValues[typeNumber].splice(index, 1);
        updateVariationLists();
        generateVariationRows();
        updateHiddenInputs();
    }
}

// Update hidden inputs with variation values
function updateHiddenInputs() {
    [1, 2].forEach(typeNumber => {
        const hiddenInput = document.getElementById(`variation-values-${typeNumber}`);
        hiddenInput.value = JSON.stringify(variationValues[typeNumber]);
    });
}

// Update variation lists display
function updateVariationLists() {
    [1, 2].forEach(typeNumber => {
        const container = document.getElementById(`values-list-${typeNumber}`);
        container.innerHTML = '';

        variationValues[typeNumber].forEach(value => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-light text-dark border me-2 mb-2';
            badge.innerHTML = `
                ${value}
                <button type="button" class="btn-close ms-2" 
                    onclick="removeValue(${typeNumber}, '${value}')"
                    style="font-size: 0.5rem;">×</button>
            `;
            container.appendChild(badge);
        });
    });
}

// Generate variation rows based on selected values
function generateVariationRows() {
    const tbody = document.getElementById('variations-body');
    tbody.innerHTML = '';

    const type1Name = document.getElementById('variation-type-1').value || '';
    const type2Name = document.getElementById('variation-type-2').value || '';

    let combinations = [];
    if (variationValues[1].length && variationValues[2].length) {
        // Two-dimensional variations
        variationValues[1].forEach(val1 => {
            variationValues[2].forEach(val2 => {
                combinations.push({
                    name: `${val1}/${val2}`,
                    combinations: {
                        [type1Name]: val1,
                        [type2Name]: val2
                    }
                });
            });
        });
    } else if (variationValues[1].length || variationValues[2].length) {
        // Single dimension variations
        const activeValues = variationValues[1].length ? variationValues[1] : variationValues[2];
        const activeTypeName = variationValues[1].length ? type1Name : type2Name;
        combinations = activeValues.map(val => ({
            name: val,
            combinations: {
                [activeTypeName]: val
            }
        }));
    }

    // Generate rows with existing data if available
    combinations.forEach((combination, index) => {
        // Find existing variation data
        const existingVariation = existingVariations.find(v => {
            // Cocok berdasarkan nama atau kombinasi
            return v.name === combination.name || 
                   (v.combinations && 
                    JSON.stringify(v.combinations) === JSON.stringify(combination.combinations));
        }) || {};
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${combination.name}</td>
            <td>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="number" 
                        name="variations[${index}][price]" 
                        class="form-control" 
                        value="${existingVariation.price || ''}"
                        placeholder="Please Enter"
                        required>
                </div>
            </td>
            <td>
                <input type="number" 
                    name="variations[${index}][stock]" 
                    class="form-control" 
                    value="${existingVariation.stock || ''}"
                    placeholder="Should be between 0-999,999"
                    required>
            </td>
            <td>
                <input type="text" 
                    name="variations[${index}][msku]" 
                    class="form-control" 
                    value="${existingVariation.msku || ''}"
                    placeholder="Please Enter"
                    required>
            </td>
            <td>
                <input type="text" 
                    name="variations[${index}][barcode]" 
                    class="form-control" 
                    value="${existingVariation.barcode || ''}"
                    placeholder="Barcode only supports letters, numb..."
                    required>
            </td>
            <td>
                <div class="variation-photo-section">
                    <div class="image-upload-container">
                        <label class="image-upload-box" style="width: 60px; height: 60px;">
                            <input type="file" 
                                class="variation-photos d-none" 
                                name="variations[${index}][photos][]"
                                accept="image/*"
                                multiple 
                                data-max-files="1"
                                data-variation-name="${combination.name}">
                            <div class="upload-placeholder">
                                <span class="plus-icon">+</span>
                            </div>
                        </label>
                        <div class="variation-photos-preview d-flex flex-wrap gap-2" 
                            id="preview-${index}">
                            ${existingVariation.photo ? `
                                <div class="image-preview position-relative" style="width: 60px; height: 60px;">
                                    <img src="${existingVariation.photo}" alt="Variation Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                    <button type="button" class="btn-close position-absolute top-0 end-0" 
                                        onclick="removeVariationPhoto(${index})"
                                        style="font-size: 0.5rem; background: white;"></button>
                                </div>
                            ` : ''}
                        </div>
                        <small class="text-muted d-block">Max 1</small>
                    </div>
                </div>
            </td>
            <td>
                ${existingVariation.values && existingVariation.values.length > 0 ? 
                    existingVariation.values.map(value => `
                        <span class="badge bg-light text-dark border me-2 mb-2">${value}</span>
                    `).join('') : ''}
            </td>
            <input type="hidden" name="variations[${index}][name]" value="${combination.name}">
            <input type="hidden" name="variations[${index}][combinations]" 
                value='${typeof combination.combinations === "string" 
                    ? combination.combinations 
                    : JSON.stringify(combination.combinations)}'>
            ${existingVariation.id ? `<input type="hidden" name="variations[${index}][id]" value="${existingVariation.id}">` : ''}
        `;
        tbody.appendChild(row);
    });
}

// Handle variation photo upload
function handleVariationPhotoUpload(event) {
    const fileInput = event.target;
    const files = fileInput.files;
    const previewContainer = fileInput.closest('.image-upload-container')
        .querySelector('.variation-photos-preview');
    const index = fileInput.name.match(/\[(\d+)\]/)[1];

    // Clear previous previews
    previewContainer.innerHTML = '';

    if (files.length > 0) {
        const file = files[0]; // Take first file

        // Validate file size
        if (file.size > CONFIG.MAX_VARIATION_PHOTO_SIZE) {
            alert(`File is too large. Maximum size is ${CONFIG.MAX_VARIATION_PHOTO_SIZE / (1024 * 1024)}MB.`);
            fileInput.value = ''; // Clear the file input
            return;
        }

        // Validate file type
        if (!CONFIG.ALLOWED_IMAGE_TYPES.includes(file.type)) {
            alert('Please upload a valid image file (JPEG, PNG, or WebP).');
            fileInput.value = ''; // Clear the file input
            return;
        }

        // Create file reader to show preview
        const reader = new FileReader();
        reader.onload = function(readerEvent) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'image-preview position-relative';
            previewDiv.style.width = '60px';
            previewDiv.style.height = '60px';

            const img = document.createElement('img');
            img.src = readerEvent.target.result;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-close position-absolute top-0 end-0';
            removeBtn.style.fontSize = '0.5rem';
            removeBtn.style.background = 'white';
            removeBtn.onclick = function() {
                fileInput.value = ''; // Clear the file input
                previewContainer.innerHTML = ''; // Remove preview
            };

            previewDiv.appendChild(img);
            previewDiv.appendChild(removeBtn);
            previewContainer.appendChild(previewDiv);
        };
        reader.readAsDataURL(file);
    }
}

// Remove variation photo
function removeVariationPhoto(index) {
    const previewDiv = document.getElementById(`preview-${index}`);
    
    // Clear the preview
    previewDiv.innerHTML = '';
    
    // Add hidden input to track photo removal
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = `variations[${index}][remove_photo]`;
    input.value = '1';
    previewDiv.appendChild(input);
}

// Prevent duplicate variation type names
function validateVariationTypeUniqueness() {
    const type1 = document.getElementById('variation-type-1').value.trim().toLowerCase();
    const type2 = document.getElementById('variation-type-2').value.trim().toLowerCase();

    if (type1 && type2 && type1 === type2) {
        alert('Variation types must be unique');
        document.getElementById('variation-type-2').value = '';
        return false;
    }
    return true;
}

// Initialize event listeners and setup
document.addEventListener('DOMContentLoaded', function() {
        // Set nama tipe variasi
        const variantType1Input = document.getElementById('variation-type-1');
        const variantType2Input = document.getElementById('variation-type-2');

        // Set nama tipe variasi dari backend
        @if(isset($variantTypes[0]['name']))
            variantType1Input.value = "{{ $variantTypes[0]['name'] }}";
        @endif

        @if(isset($variantTypes[1]['name']))
            variantType2Input.value = "{{ $variantTypes[1]['name'] }}";
        @endif

        // Inisialisasi daftar nilai variasi
        updateVariationLists();
        generateVariationRows();
        updateHiddenInputs();

        // Pastikan checkbox variasi diatur dengan benar
        const variationCheckbox = document.getElementById('product-variations');
        variationCheckbox.checked = {{ $product->has_variations ? 'true' : 'false' }};
        variationCheckbox.dispatchEvent(new Event('change'));
    });

    // Modifikasi fungsi toggleVariationFields untuk mempertahankan data
    function toggleVariationFields() {
        const variationCheckbox = document.getElementById('product-variations');
        const variationFields = document.getElementById('variation-fields');
        const baseFields = document.getElementById('base-fields');

        if (variationCheckbox.checked) {
            variationFields.style.display = 'block';
            baseFields.style.display = 'none';
            
            // Regenerate variation rows jika ada nilai
            if (variationValues[1].length || variationValues[2].length) {
                updateVariationLists();
                generateVariationRows();
                updateHiddenInputs();
            }
        } else {
            variationFields.style.display = 'none';
            baseFields.style.display = 'block';
            
            // Hanya kosongkan nilai, jangan hapus nama tipe
            variationValues = { 1: [], 2: [] };
            updateVariationLists();
            generateVariationRows();
            updateHiddenInputs();
        }
    }

    // Tambahan fungsi untuk memastikan nama tipe variasi tetap ada
    function preserveVariationTypeNames() {
        const type1Input = document.getElementById('variation-type-1');
        const type2Input = document.getElementById('variation-type-2');

        // Pastikan nama tipe variasi tidak hilang saat toggle
        type1Input.setAttribute('data-original-name', type1Input.value);
        type2Input.setAttribute('data-original-name', type2Input.value);
    }

    // Panggil fungsi preserve names saat inisialisasi
    document.addEventListener('DOMContentLoaded', preserveVariationTypeNames);

</script>

            

            <!-- Media Settings Section -->
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
                            <div class="product-image-container d-flex gap-3 flex-wrap">
                                <!-- Existing Product Images -->
                                @foreach($product->images as $image)
                                    <div class="image-preview position-relative" style="width: 120px; height: 120px;">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                            alt="Product Image"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                        <span class="remove-image text-danger position-absolute top-0 end-0" 
                                            data-image-id="{{ $image->id }}">
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
            <input type="hidden" name="removed_images" id="removed-images" value="">

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const maxImages = 9;
                const imageInput = document.querySelector('.product-image-input');
                const uploadPlaceholder = document.querySelector('.upload-placeholder');
                const imageContainer = document.querySelector('.product-image-container');
                const removedImagesInput = document.getElementById('removed-images');

                // Handle click on upload placeholder
                uploadPlaceholder.addEventListener('click', function() {
                    const currentImages = document.querySelectorAll('.image-preview').length;
                    if (currentImages < maxImages) {
                        imageInput.click();
                    } else {
                        alert('Maximum 9 images allowed');
                    }
                });

                // Handle file selection
                imageInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    const currentImages = document.querySelectorAll('.image-preview').length;
                    
                    if (currentImages + files.length > maxImages) {
                        alert(`You can only add ${maxImages - currentImages} more images`);
                        return;
                    }

                    files.forEach(file => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewDiv = document.createElement('div');
                                previewDiv.className = 'image-preview position-relative';
                                previewDiv.style.width = '120px';
                                previewDiv.style.height = '120px';
                                
                                previewDiv.innerHTML = `
                                    <img src="${e.target.result}" 
                                        alt="Product Image" 
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                    <span class="remove-image text-danger position-absolute top-0 end-0">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                `;
                                
                                imageContainer.appendChild(previewDiv);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                });

                // Handle image removal (both existing and new images)
                imageContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-image')) {
                        const previewDiv = e.target.closest('.image-preview');
                        const imageId = e.target.closest('.remove-image').getAttribute('data-image-id');
                        
                        // If it's an existing image, add to removed images list
                        if (imageId) {
                            const currentRemoved = removedImagesInput.value ? 
                                removedImagesInput.value.split(',') : [];
                            currentRemoved.push(imageId);
                            removedImagesInput.value = currentRemoved.join(',');
                        }
                        
                        previewDiv.remove();
                    }
                });
            });
            </script>

            <!-- Button Simpan -->
            <button type="submit" class="btn btn-primary mt-3" style="border-radius: 8px; width: 100%;">Simpan</button>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop