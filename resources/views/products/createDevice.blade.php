@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row align-items-center" 
         style="border: 1px solid #ccc; border-radius: 15px; padding: 20px; background-color: #f8f9fa; ">
        <div class="col-md-6">
            <h4 style="margin: 0; color: #343a40; font-weight: bold;">Create Product</h4>
        </div>
    </div>
@stop

@section('content')

    <style>

    .chevron {
        width: 16px;
        height: 16px;
        margin-left: auto; /* Push to right */
        margin-top: auto; /* Center vertically */
        margin-bottom: auto;
    }


    .category-dropdown {
        position: relative;
        width: 300px;
    }

    .dropdown-select {
        border: 1px solid #ddd;
        padding: 10px;
        cursor: pointer;
        background: #fff;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        list-style: none;
        padding: 5px 0;
    }

    .dropdown-menu li {
        padding: 10px;
        cursor: pointer;
    }

    .dropdown-menu li:hover {
        background: #f0f0f0;
    }

    </style>
    <div class="container mt-4" style="border: 1px solid #ccc; border-radius: 15px; padding: 40px; background-color: #f8f9fa; ">


        <!-- Pilihan Barang atau Jasa -->
        <div class="form-group">
            <label for="type">Pilih Tipe:</label>
            <select id="type" class="form-control" style="border-radius: 8px;">
                <option value="product" selected>Produk</option>
                <option value="service">Jasa</option>
            </select>
        </div>

        <!-- Form Tambah Produk -->
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="product-form">
            <h4 style="font-weight: bold;">Basic Information:</h4>

            <div class="form-group">
                <label for="name">Master Product Name:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Please Enter" maxlength="300" required style="border-radius: 8px;">
                <small>0/300</small>
            </div>

            <div class="form-group">
                <label for="spu">SPU:</label>
                <input type="text" id="spu" name="spu" class="form-control" placeholder="Please Enter" maxlength="200" required style="border-radius: 8px;">
                <small>0/200</small>
            </div>

            <!-- HTML -->
<div class="form-group">
    <label for="fullCategoryId">Master Category:</label>
    <div class="category-dropdown" style="border-radius: 8px;">
        <div class="dropdown-select" id="categorySelect">
            <span>Select Master Category</span>
            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" />
            </svg>
        </div>
        <div class="dropdown-menu" id="categoryMenu"></div>
    </div>
    <input type="hidden" 
        id="fullCategoryId" 
        name="fullCategoryId" 
        required
        oninvalid="this.setCustomValidity('Silakan pilih Master Category')"
        oninput="this.setCustomValidity('')">
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchCategories();
});

async function fetchCategories() {
    try {
        const response = await fetch('/products/get-categories');
        const data = await response.json();
        if (data.success) {
            populateDropdown(data.data);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function populateDropdown(categories) {
   const menu = document.getElementById('categoryMenu');
   const select = document.getElementById('categorySelect');
   const hiddenInput = document.getElementById('fullCategoryId');
   
   function addCategoryWithChildren(category, level = 0) {
       // Add parent
       const item = document.createElement('div');
       item.className = 'dropdown-item';
       item.textContent = '  '.repeat(level) + category.name;
       item.dataset.value = category.id;
       
       item.onclick = function() {
           select.querySelector('span').textContent = category.name;
           hiddenInput.value = category.id;
           menu.style.display = 'none';
       };
       
       menu.appendChild(item);
       
       // Add children recursively
       if (category.children && category.children.length > 0) {
           category.children.forEach(child => {
               addCategoryWithChildren(child, level + 1);
           });
       }
   }

   // Start with top-level categories
   categories.forEach(category => addCategoryWithChildren(category));
   
   select.onclick = function() {
       menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
   };
}
</script>

<style>
.category-dropdown {
    position: relative;
    width: 100%;
    max-width: 300px;
}

.dropdown-select {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border: 1px solid #ccc;
    cursor: pointer;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ccc;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
}

.dropdown-item {
    padding: 10px;
    cursor: pointer;
}

.dropdown-item:hover {
    background: #f5f5f5;
}
</style>

            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" class="form-control" placeholder="1-20 digits of English, Chinese, numbers, spaces and - _ & %" maxlength="20">
                <small>0/20</small>
            </div>


            <div class="form-group">
            <label for="saleStatus">Channel Selling Status:</label>
            <select id="saleStatus" name="saleStatus"class="form-control" style="border-radius: 8px;">
                <option value="FOR_SALE">For sale</option>
                <option value="HOT_SALE">Hot sale</option>
                <option value="NEW_ARRIVAL">New arrival</option>
                <option value="SALE_ENDED">Sale ended</option>
            </select>
            </div>

            <div class="form-group">
                <label for="condition">Condition:</label>
                <div>
                    <label class="radio-inline">
                        <input type="radio" name="condition" value="NEW" required> New
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="condition" value="USED" required> Used
                    </label>
                </div>
            </div>


            <div class="form-group">
                <label for="shelf-life">Shelf Life:</label>
                <select id="shelf-life" name="hasSelfLife" class="form-control" style="border-radius: 8px;">
                    <option value="false">No Shelf Life</option>
                    <option value="true">Custom</option>
                </select>
            </div>

            <div id="custom-shelf-life" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shelf-life-duration">Shelf Life Duration (days):</label>
                            <input type="number" id="shelf-life-duration" name="shelfLifeDuration" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inbound-limit">Inbound Limit:</label>
                            <input type="number" id="inbound-limit" name="inboundLimit" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="outbound-limit">Outbound Limit:</label>
                            <input type="number" id="outbound-limit" name="outboundLimit" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>


            <script>
                document.getElementById('shelf-life').addEventListener('change', function() {
                    const customShelfLife = document.getElementById('custom-shelf-life');
                    const shelfLifeInputs = customShelfLife.querySelectorAll('input');
                    
                    if (this.value === 'true') {
                        customShelfLife.style.display = 'block';
                        // Aktifkan required untuk input fields
                        shelfLifeInputs.forEach(input => input.required = true);
                    } else {
                        customShelfLife.style.display = 'none';
                        // Nonaktifkan required dan kosongkan nilai
                        shelfLifeInputs.forEach(input => {
                            input.required = false;
                            input.value = ''; // Kosongkan nilai
                        });
                    }
                });

            </script>

            <div class="form-group">
            <label for="minPurchase">Minimum purchase quantity:</label>
            <input type="number" id="minPurchase" name="minPurchase" class="form-control" value="1" required style="border-radius: 8px;">
            </div>

            <div class="form-group">
                <label for="shortDescription">Short description:</label>
                <textarea id="shortDescription" name="shortDescription" class="form-control" placeholder="Please Enter" required style="border-radius: 8px;">{{ old('shortDescription') }}</textarea>
            </div>

            <div class="form-group">
            <label for="long-description">Long description:</label>
            <textarea id="long-description" class="form-control" name='description' placeholder="Type your description here and apply it to your product" maxlength="7000" required style="border-radius: 8px;"></textarea>
            <small>0/7000</small>
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
                    style="width: auto;">
            </div>

            <div id="variation-fields" style="display: none;">
                <!-- First Variation Type -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="variation-type-1">First Variation Type:</label>
                            <input type="text" 
                                id="variation-type-1" 
                                name="variantTypes[0][name]" 
                                class="form-control" 
                                placeholder="e.g., color">
                        </div>
                    </div>
                    
                    <!-- First Variation Type -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="variation-values-1">Option Values:</label>
                            <div class="tags-input-wrapper">
                                <div class="tags-container" id="tags-container-1"></div>
                                <input type="text" 
                                    id="tag-input-1"
                                    class="form-control tag-input" 
                                    placeholder="Type and press Enter to add value">
                                <input type="hidden" 
                                    name="variantTypes[0][values]" 
                                    id="values-hidden-1">
                            </div>
                        </div>
                    </div>

                    <style>
                    .tags-input-wrapper {
                        border: 1px solid #ced4da;
                        border-radius: 0.25rem;
                        padding: 0.375rem;
                        background: #fff;
                    }

                    .tags-container {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 5px;
                        margin-bottom: 5px;
                        min-height: 32px;
                    }

                    .tag {
                        background: #e9ecef;
                        border-radius: 3px;
                        padding: 2px 8px;
                        display: flex;
                        align-items: center;
                        gap: 5px;
                    }

                    .tag-remove {
                        cursor: pointer;
                        color: #6c757d;
                        font-weight: bold;
                    }

                    .tag-input {
                        border: none !important;
                        box-shadow: none !important;
                        padding: 0 !important;
                        margin-top: 5px;
                    }

                    .tag-input:focus {
                        outline: none !important;
                    }
                    </style>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        function parseValues(value) {
                            if (!value) return [];
                            return value.split(',')
                                    .map(item => item.trim())
                                    .filter(item => item);
                        }
                        
                        function initializeTagsInput(containerId, inputId, hiddenId) {
                            const tagsContainer = document.getElementById(containerId);
                            const tagInput = document.getElementById(inputId);
                            const hiddenInput = document.getElementById(hiddenId);
                            
                            // Ambil nilai awal dari hidden input yang sudah dibersihkan
                            let tags = parseValues(hiddenInput.value);
                            
                            function renderTags() {
                                tagsContainer.innerHTML = '';
                                tags.forEach((tag, index) => {
                                    const tagElement = document.createElement('span');
                                    tagElement.className = 'tag';
                                    tagElement.innerHTML = `
                                        ${tag}
                                        <span class="tag-remove" data-index="${index}">&times;</span>
                                    `;
                                    tagsContainer.appendChild(tagElement);
                                });
                                // Simpan sebagai string biasa dengan pemisah koma
                                hiddenInput.value = tags.join(',');
                            }
                            
                            // Add new tag
                            tagInput.addEventListener('keydown', function(e) {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    const value = this.value.trim();
                                    if (value && !tags.includes(value)) {
                                        tags.push(value);
                                        renderTags();
                                        this.value = '';
                                    }
                                }
                            });
                            
                            // Remove tag
                            tagsContainer.addEventListener('click', function(e) {
                                if (e.target.classList.contains('tag-remove')) {
                                    const index = parseInt(e.target.dataset.index);
                                    tags.splice(index, 1);
                                    renderTags();
                                }
                            });
                            
                            // Initial render
                            renderTags();
                        }
                        
                        // Initialize both variation types
                        initializeTagsInput('tags-container-1', 'tag-input-1', 'values-hidden-1');
                        initializeTagsInput('tags-container-2', 'tag-input-2', 'values-hidden-2');
                    });
                    </script>
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
                                placeholder="e.g., size">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="variation-values-2">Option Values:</label>
                            <div class="tags-input-wrapper">
                                <div class="tags-container" id="tags-container-2"></div>
                                <input type="text" 
                                    id="tag-input-2"
                                    class="form-control tag-input" 
                                    placeholder="Type and press Enter to add value">
                                <input type="hidden" 
                                    name="variantTypes[1][values]" 
                                    id="values-hidden-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rest of the variations table remains the same -->
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

            <!-- Base Fields -->
            <div id="base-fields" style="display: block;">
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
                                            value="{{ old('basePrice') }}"
                                            placeholder="Please Enter">
                                    </div>
                                </td>
                                <td>
                                    <input type="number" 
                                        name="baseStock" 
                                        class="form-control" 
                                        value="{{ old('baseStock') }}"
                                        placeholder="Should be between 0-999,999">
                                </td>
                                <td>
                                    <input type="text" 
                                        name="baseMsku" 
                                        class="form-control" 
                                        value="{{ old('baseMsku') }}"
                                        placeholder="Please Enter">
                                </td>
                                <td>
                                    <input type="text" 
                                        name="baseBarcode" 
                                        class="form-control" 
                                        value="{{ old('baseBarcode') }}"
                                        placeholder="Barcode only supports letters, numb...">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <style>
            .product-image-container {
                display: flex; /* Flexbox untuk tata letak horizontal */
                gap: 10px; /* Jarak antar gambar */
                flex-wrap: wrap; /* Agar gambar turun ke baris baru jika ruang tidak cukup */
                width: 100%; /* Kontainer menggunakan lebar penuh */
            }

            .image-upload-box {
                width: 120px; /* Lebar konsisten */
                height: 120px; /* Tinggi konsisten */
                position: relative;
            }

            .image-preview {
                width: 120px; /* Ukuran konsisten */
                height: 120px; /* Sama dengan upload box */
                position: relative;
            }

            .image-preview img {
                width: 100%; /* Memastikan gambar memenuhi kontainer */
                height: 100%;
                object-fit: cover; /* Gambar tidak terdistorsi */
            }

            .upload-placeholder {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                height: 100%;
                border: 1px dashed #ccc; /* Garis putus-putus untuk placeholder */
                cursor: pointer;
            }

            .remove-image {
                position: absolute;
                top: 5px;
                right: 5px;
                z-index: 10; /* Pastikan tombol hapus berada di atas gambar */
                cursor: pointer;
            }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Existing tags input initialization code...
                    document.getElementById('variation-type-1').addEventListener('input', generateVariationRows);
                    document.getElementById('variation-type-2').addEventListener('input', generateVariationRows);
                    document.getElementById('values-hidden-1').addEventListener('change', generateVariationRows);
                    document.getElementById('values-hidden-2').addEventListener('change', generateVariationRows);

                    // Initial generation of variation rows if has variations is checked
                    const hasVariationsCheckbox = document.getElementById('product-variations');
                    if (hasVariationsCheckbox.checked) {
                        generateVariationRows();
                    }

                    // Add listener for checkbox changes
                    hasVariationsCheckbox.addEventListener('change', function(e) {
                        const variationFields = document.getElementById('variation-fields');
                        const baseFields = document.getElementById('base-fields');

                        variationFields.style.display = e.target.checked ? 'block' : 'none';
                        baseFields.style.display = e.target.checked ? 'none' : 'block';

                        if (e.target.checked) {
                            generateVariationRows();
                        }
                    });

                    // Also trigger generation when hidden inputs change (when tags are added/removed)
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === "attributes" && mutation.attributeName === "value") {
                                generateVariationRows();
                            }
                        });
                    });

                    // Observe both hidden inputs for changes
                    const hiddenInput1 = document.getElementById('values-hidden-1');
                    const hiddenInput2 = document.getElementById('values-hidden-2');

                    observer.observe(hiddenInput1, { attributes: true });
                    observer.observe(hiddenInput2, { attributes: true });
                });

                const existingVariations = [];

                // Definisikan fungsi generateVariationRows sekali saja
                function generateVariationRows() {
                const tbody = document.getElementById('variations-body');
                tbody.innerHTML = '';

                // Get variation type names
                const type1Name = document.getElementById('variation-type-1').value || '';
                const type2Name = document.getElementById('variation-type-2').value || '';

                // Get values from hidden inputs
                const values1 = document.getElementById('values-hidden-1').value.split(',').filter(item => item.trim());
                const values2 = document.getElementById('values-hidden-2').value.split(',').filter(item => item.trim());

                // Generate combinations
                let combinations = [];
                if (values1.length && values2.length) {
                values1.forEach(val1 => {
                values2.forEach(val2 => {
                    combinations.push({
                        name: `${val1}/${val2}`,
                        combinations: {
                            [type1Name]: val1,
                            [type2Name]: val2
                        }
                    });
                });
                });
                } else if (values1.length || values2.length) {
                const activeValues = values1.length ? values1 : values2;
                const activeTypeName = values1.length ? type1Name : type2Name;
                combinations = activeValues.map(val => ({
                name: val,
                combinations: {
                    [activeTypeName]: val
                }
                }));
                }

                // Create rows
                combinations.forEach((combination, index) => {
                const existingVariation = existingVariations.find(v => v.name === combination.name);

                let imagesPreviewHtml = '';
                if (existingVariation && existingVariation.variant_image_path) {
                imagesPreviewHtml = `
                    <div class="existing-image position-relative" style="margin: 2px;">
                        <img src="/storage/${existingVariation.variant_image_path}" 
                            alt="${combination.name}" 
                            style="width: 50px; height: 50px; object-fit: cover;">
                        <span class="remove-variation-image position-absolute top-0 end-0" 
                            style="cursor: pointer; background: rgba(255,255,255,0.8); border-radius: 50%; padding: 2px;"
                            data-image-id="${existingVariation.id}">
                            <i class="fas fa-times" style="font-size: 12px;"></i>
                        </span>
                        <input type="hidden" name="variations[${index}][existing_variant_image]" value="${existingVariation.variant_image_path}">
                    </div>
                `;
                }

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
                            value="${existingVariation ? existingVariation.price : ''}"
                            placeholder="Please Enter"
                            required>
                    </div>
                </td>
                <td>
                    <input type="number" 
                        name="variations[${index}][stock]" 
                        class="form-control" 
                        value="${existingVariation ? existingVariation.stock : ''}"
                        placeholder="Should be between 0-999,999"
                        required>
                </td>
                <td>
                    <input type="text" 
                        name="variations[${index}][msku]" 
                        class="form-control" 
                        value="${existingVariation ? existingVariation.msku : ''}"
                        placeholder="Please Enter"
                        required>
                </td>
                <td>
                    <input type="text" 
                        name="variations[${index}][barcode]" 
                        class="form-control" 
                        value="${existingVariation ? existingVariation.barcode : ''}"
                        placeholder="Barcode only supports letters, numb..."
                        required>
                </td>

                <td>
                    <div class="variation-photo-section">
                        <div class="image-upload-container">
                            <!-- Upload New Image -->
                            <label class="image-upload-box d-flex justify-content-center align-items-center" 
                                style="width: 60px; height: 60px; border: 1px dashed #ccc; cursor: pointer;">
                                <input type="file" 
                                class="variation-photos d-none" 
                                name="variations[${index}][photos][]"
                                accept="image/*"
                                multiple
                                data-preview-id="preview-${index}"
                                data-variation-name="${combination.name}">
                                <span class="plus-icon">+</span>
                            </label>

                            <!-- Images Preview Area -->
                            <div class="variation-photos-preview mt-2" id="preview-${index}">
                                ${existingVariation && existingVariation.variant_image_path ? `
                                    <div class="existing-image position-relative d-inline-block me-2">
                                        <img src="/storage/${existingVariation.variant_image_path}" 
                                            alt="${combination.name}" 
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <span class="remove-variation-image position-absolute top-0 end-0" 
                                            style="cursor: pointer; background: rgba(255,255,255,0.8); border-radius: 50%; padding: 2px;"
                                            data-image-id="${existingVariation.id}">
                                            <i class="fas fa-times" style="font-size: 12px;"></i>
                                        </span>
                                        <input type="hidden" name="variations[${index}][existing_variant_image]" value="${existingVariation.variant_image_path}">
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </td>
                <input type="hidden" name="variations[${index}][name]" value="${combination.name}">
                <input type="hidden" name="variations[${index}][combinations]" value='${JSON.stringify(combination.combinations)}'>
                ${existingVariation ? `<input type="hidden" name="variations[${index}][id]" value="${existingVariation.id}">` : ''}
                `;
                tbody.appendChild(row);
                });
                // Tambahkan di akhir fungsi generateVariationRows():

                // Handle new image upload preview for variations
                document.querySelectorAll('.variation-photos').forEach(input => {
                    input.addEventListener('change', function(e) {
                        const previewId = this.getAttribute('data-preview-id');
                        const previewContainer = document.getElementById(previewId);
                        const files = Array.from(this.files);

                        // Clear previous previews
                        previewContainer.innerHTML = '';

                        files.forEach(file => {
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const previewDiv = document.createElement('div');
                                    previewDiv.className = 'variation-image-preview position-relative d-inline-block me-2';
                                    previewDiv.innerHTML = `
                                        <img src="${e.target.result}" 
                                            alt="Variation Image" 
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <span class="remove-variation-image position-absolute top-0 end-0" 
                                            style="cursor: pointer; background: rgba(255,255,255,0.8); 
                                            border-radius: 50%; padding: 2px;">
                                            <i class="fas fa-times" style="font-size: 12px;"></i>
                                        </span>
                                    `;
                                    
                                    // Add remove functionality
                                    const removeButton = previewDiv.querySelector('.remove-variation-image');
                                    removeButton.addEventListener('click', () => {
                                        previewContainer.removeChild(previewDiv);
                                        // Optional: Remove corresponding file from input
                                        const dataTransfer = new DataTransfer();
                                        Array.from(input.files)
                                            .filter(f => f !== file)
                                            .forEach(f => dataTransfer.items.add(f));
                                        input.files = dataTransfer.files;
                                    });

                                    previewContainer.appendChild(previewDiv);
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    });
                });

                // Add event listeners for removing variation images
                document.querySelectorAll('.remove-variation-image').forEach(button => {
                button.addEventListener('click', function() {
                const imageId = this.dataset.imageId;
                const container = this.closest('.existing-image');
                if (container) {
                    // Add to removed images list
                    const removedImagesInput = document.getElementById('removed-variation-images') || 
                        document.createElement('input');
                    removedImagesInput.type = 'hidden';
                    removedImagesInput.id = 'removed-variation-images';
                    removedImagesInput.name = 'removed_variation_images[]';
                    removedImagesInput.value = imageId;
                    document.querySelector('form').appendChild(removedImagesInput);
                    
                    // Remove the image container
                    container.remove();
                }
                });
                });
                }

                document.addEventListener('DOMContentLoaded', function() {
                const hasVariationsCheckbox = document.getElementById('product-variations');
                const variationFields = document.getElementById('variation-fields');
                const baseFields = document.getElementById('base-fields');
                const variationsTable = document.getElementById('variations-table');
                const variationsBody = document.getElementById('variations-body');
                const baseTable = document.querySelector('#base-fields table');

                // Reset variasi
                function resetVariations() {
                // Reset variant type inputs
                document.getElementById('variation-type-1').value = '';
                document.getElementById('variation-type-2').value = '';

                // Reset hidden inputs untuk tag values
                document.getElementById('values-hidden-1').value = '';
                document.getElementById('values-hidden-2').value = '';

                // Reset tags containers
                document.getElementById('tags-container-1').innerHTML = '';
                document.getElementById('tags-container-2').innerHTML = '';
                }

                hasVariationsCheckbox.addEventListener('change', function(e) {
                if (e.target.checked) {
                // Tampilkan area variasi dan tabel variasi
                variationFields.style.display = 'block';
                variationsTable.style.display = 'table';

                // Sembunyikan area base dan tabel base
                baseFields.style.display = 'none';
                baseTable.style.display = 'none';

                // Jika sebelumnya tidak ada variasi, generate baris kosong
                if (variationsBody.innerHTML.trim() === '') {
                generateVariationRows();
                }
                } else {
                // Konfirmasi penghapusan variasi
                const confirmReset = confirm('Apakah Anda yakin ingin menghapus semua variasi?');

                if (confirmReset) {
                // Sembunyikan area variasi dan tabel variasi
                variationFields.style.display = 'none';
                variationsTable.style.display = 'none';

                // Tampilkan area base dan tabel base
                baseFields.style.display = 'block';
                baseTable.style.display = 'table';

                // Kosongkan baris variasi
                variationsBody.innerHTML = '';

                // Reset input variasi
                resetVariations();
                } else {
                // Jika dibatalkan, kembalikan checkbox ke state sebelumnya
                this.checked = true;
                }
                }
                });

                // Kondisi awal saat halaman dimuat
                if (hasVariationsCheckbox.checked) {
                variationFields.style.display = 'block';
                variationsTable.style.display = 'table';
                baseFields.style.display = 'none';
                baseTable.style.display = 'none';

                // Generate baris variasi jika belum ada
                if (variationsBody.innerHTML.trim() === '') {
                generateVariationRows();
                }
                } else {
                variationFields.style.display = 'none';
                variationsTable.style.display = 'none';
                baseFields.style.display = 'block';
                baseTable.style.display = 'table';
                }
                });
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
                
                <!-- Container for displaying uploaded images -->
                <div class="product-image-container d-flex gap-3 flex-wrap">
                    <!-- Images will be dynamically added here -->
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
    const removedImagesInput = document.getElementById('removed-images') || 
        document.createElement('input');
    
    removedImagesInput.type = 'hidden';
    removedImagesInput.id = 'removed-images';
    removedImagesInput.name = 'removed_images';
    removedImagesInput.value = '';
    
    // Append hidden input if not already in the form
    if (!document.getElementById('removed-images')) {
        document.querySelector('form').appendChild(removedImagesInput);
    }

    // Handle click on upload placeholder
    uploadPlaceholder.addEventListener('click', function() {
        const currentImages = imageContainer.querySelectorAll('.image-preview').length;
        if (currentImages < maxImages) {
            imageInput.click();
        } else {
            alert('Maximum 9 images allowed');
        }
    });

    // Handle file selection
    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const currentImages = imageContainer.querySelectorAll('.image-preview').length;
        
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

    // Handle image removal
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
            
            <style>
                .section-header {
                    cursor: pointer;
                    padding: 15px;
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    border-radius: 4px;
                    margin-bottom: 10px;
                }
                .section-header:hover {
                    background-color: #e9ecef;
                }
                .section-content {
                    padding: 15px;
                    border: 1px solid #dee2e6;
                    border-radius: 4px;
                    margin-bottom: 20px;
                }
                .rotate {
                    transform: rotate(180deg);
                    transition: transform 0.3s ease;
                }
                .collapse-icon {
                    transition: transform 0.3s ease;
                }
                .form-group {
                    margin-bottom: 1rem;
                }
                input {
                    border-radius: 8px !important;
                }
                .radio-group {
                    display: flex;
                    gap: 20px;
                }
            </style>

            <div>
                <h5 style="font-weight: bold;">Delivery: (optional)</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="length">Panjang (cm):</label>
                            <input type="number" name="length" id="length" class="form-control" placeholder="Masukkan panjang">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="width">Lebar (cm):</label>
                            <input type="number" name="width" id="width" class="form-control" placeholder="Masukkan lebar">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="height">Tinggi (cm):</label>
                            <input type="number" name="height" id="height" class="form-control" placeholder="Masukkan tinggi">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="weight">Berat (g):</label>
                            <input type="number" name="weight" id="weight" class="form-control" placeholder="Masukkan berat">
                        </div>
                    </div>
                </div>
                <label for="pre-order">Pre-Order:</label>
                <div>
                    <label class="radio-inline">
                        <input type="radio" name="preOrder" value="yes"> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="preOrder" value="no"> No
                    </label>
                </div>


                <h4 style="font-weight: bold;">Customs Information: (Optional)</h4>

                <div id="customs-info">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="customs-chinese-name">Customs Chinese Name:</label>
                                <input type="text" name="customs_chinese_name" id="customs-chinese-name" class="form-control" placeholder="Please Enter" maxlength="200" style="border-radius: 8px;">
                                <small>0/200</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="customs-english-name">Customs English Name:</label>
                                <input type="text" name="customs_english_name" id="customs-english-name" class="form-control" placeholder="Please Enter" maxlength="200" style="border-radius: 8px;">
                                <small>0/200</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hs-code">HS Code:</label>
                                <input type="text" name="hs_code" id="hs-code" class="form-control" placeholder="Please Enter" maxlength="200" style="border-radius: 8px;">
                                <small>0/200</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice-amount">Invoice Amount:</label>
                                <input type="text" name="invoice_amount" id="invoice-amount" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gross-weight">Gross Weight (g):</label>
                                <input type="number" name="gross_weight" id="gross-weight" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                            </div>
                        </div>
                    </div>
                </div>

                <h4 style="font-weight: bold;">Cost Information: (Optional)</h4>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="source-url">Source URL:</label>
                            <input type="text" name="source_url" id="source-url" class="form-control" placeholder="Please Enter" maxlength="150" style="border-radius: 8px;">
                            <small>0/150</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="purchase-duration">Purchase Duration:</label>
                            <input type="text" name="purchase_duration" id="purchase-duration" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sales-tax-amount">Sales Tax Amount:</label>
                            <input type="text" name="sales_tax_amount" id="sales-tax-amount" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <h4 style="font-weight: bold;">Other Information:(Optional)</h4>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="remarks1">Remarks1:</label>
                            <input type="text" name="remarks1" id="remarks1" class="form-control" placeholder="1-50 digits of English, Chinese, numbers, spaces and - _ & %" maxlength="50" style="border-radius: 8px;">
                            <small>0/50</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="remarks2">Remarks2:</label>
                            <input type="text" name="remarks2" id="remarks2" class="form-control" placeholder="1-50 digits of English, Chinese, numbers, spaces and - _ & %" maxlength="50" style="border-radius: 8px;">
                            <small>0/50</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="remarks3">Remarks3:</label>
                            <input type="text" name="remarks3" id="remarks3" class="form-control" placeholder="1-50 digits of English, Chinese, numbers, spaces and - _ & %" maxlength="50" style="border-radius: 8px;">
                            <small>0/50</small>
                        </div>
                    </div>
                </div>
            </div>
        
            <button type="submit" class="btn btn-primary mt-3" style="border-radius: 8px; width: 100%;">Simpan</button>
        </form>
        </div>          


        <!-- Form Tambah Jasa -->
        <form id="service-form" action="{{ route('services.store') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="type" value="service">
            
            <!-- Nama Jasa & Harga Beli -->
            <div class="row">
                <div class="col-md-6">
                    <label for="nama-service">Nama Jasa:</label>
                    <input type="text" id="nama-service" name="nama_service" class="form-control @error('nama_service') is-invalid @enderror" placeholder="Masukkan Nama Jasa" required style="border-radius: 8px;" value="{{ old('nama_service') }}">
                    @error('nama_service')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="harga-beli-service">Harga Beli (Rp):</label>
                    <input type="number" id="harga-beli-service" name="harga_beli_service" class="form-control @error('harga_beli_service') is-invalid @enderror" placeholder="0" required style="border-radius: 8px;" value="{{ old('harga_beli_service') }}">
                    @error('harga_beli_service')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Form Tambah Kategori & Satuan Perhitungan -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="kategori">Kategori:</label>
                    <input type="text" id="kategori" name="kategori" class="form-control @error('kategori') is-invalid @enderror" placeholder="Masukkan Kategori" required style="border-radius: 8px;" value="{{ old('kategori') }}">
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="satuan">Satuan Perhitungan:</label>
                    <input type="text" id="satuan" name="satuan" class="form-control @error('satuan') is-invalid @enderror" placeholder="Masukkan Satuan Perhitungan" required style="border-radius: 8px;" value="{{ old('satuan') }}">
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Harga Jual -->
            <div class="form-group mt-3">
                <label for="harga-jual">Harga Jual (Rp):</label>
                <input type="number" id="harga-jual" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" placeholder="0" required style="border-radius: 8px;" value="{{ old('harga_jual') }}">
                @error('harga_jual')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Alert Success/Error -->
            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Button Simpan -->
            <button type="submit" class="btn btn-primary mt-3" style="border-radius: 8px; width: 100%;">Simpan</button>
        </form>
        </div>

        <script>

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
