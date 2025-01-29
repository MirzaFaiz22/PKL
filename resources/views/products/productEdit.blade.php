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
                
                <div class="form-group">
                        <label for="name">Master Product Name:</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Please Enter"
                            maxlength="300" required style="border-radius: 8px;" value="{{ $product->name }}">
                        <small>0/300</small>
                    </div>

                    <div class="form-group">
                        <label for="spu">SPU:</label>
                        <input type="text" id="spu" name="spu" class="form-control" placeholder="Please Enter"
                            maxlength="200" required style="border-radius: 8px;" value="{{ $product->spu }}">
                        <small>0/200</small>
                    </div>


                    <div class="form-group">
                        <label for="fullCategoryId">Master Category:</label>
                        <div class="category-dropdown">
                            <div class="dropdown-select" id="categorySelect">
                                <span>
                                    @if ($product->fullCategoryId)
                                        {{ implode(' > ', $product->getCategoryPath()) }}
                                    @else
                                        Select Master Category
                                    @endif
                                </span>
                                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 18l6-6-6-6" />
                                </svg>
                            </div>
                            <div class="dropdown-menu" id="categoryMenu"></div>
                        </div>
                        <input type="hidden" id="fullCategoryId" name="fullCategoryId"
                            value="{{ $product->fullCategoryId ? json_encode($product->fullCategoryId) : '' }}" />
                    </div>

                    <div class="form-group">
                        <label for="brand">Brand:</label>
                        <input type="text" id="brand" name="brand" class="form-control"
                            placeholder="1-20 digits of English, Chinese, numbers, spaces and - _ & %" maxlength="20"
                            value="{{ $product->brand }}">
                        <small>0/20</small>
                    </div>

                    <div class="form-group">
                        <label for="saleStatus">Channel Selling Status:</label>
                        <select id="saleStatus" name="saleStatus" class="form-control" style="border-radius: 8px;">
                            <option value="FOR_SALE" {{ $product->saleStatus == 'FOR_SALE' ? 'selected' : '' }}>For sale
                            </option>
                            <option value="HOT_SALE" {{ $product->saleStatus == 'HOT_SALE' ? 'selected' : '' }}>Hot sale
                            </option>
                            <option value="NEW_ARRIVAL" {{ $product->saleStatus == 'NEW_ARRIVAL' ? 'selected' : '' }}>New
                                arrival</option>
                            <option value="SALE_ENDED" {{ $product->saleStatus == 'SALE_ENDED' ? 'selected' : '' }}>Sale ended
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="condition">Condition:</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" name="condition" value="NEW"
                                    {{ $product->condition == 'NEW' ? 'checked' : '' }} required> New
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="condition" value="USED"
                                    {{ $product->condition == 'USED' ? 'checked' : '' }} required> Used
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="shelf-life">Shelf Life:</label>
                        <select id="shelf-life" name="hasSelfLife" class="form-control" style="border-radius: 8px;">
                            <option value="false" {{ !$product->hasSelfLife ? 'selected' : '' }}>No Shelf Life</option>
                            <option value="true" {{ $product->hasSelfLife ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>

                    <div id="custom-shelf-life" style="display: {{ $product->hasSelfLife ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shelf-life-duration">Shelf Life Duration (days):</label>
                                    <input type="number" id="shelf-life-duration" name="shelfLifeDuration" class="form-control"
                                        placeholder="Please Enter" style="border-radius: 8px;"
                                        value="{{ $product->shelfLifeDuration }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inbound-limit">Inbound Limit:</label>
                                    <input type="number" id="inbound-limit" name="inboundLimit" class="form-control"
                                        placeholder="Please Enter" style="border-radius: 8px;"
                                        value="{{ $product->inboundLimit }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="outbound-limit">Outbound Limit:</label>
                                    <input type="number" id="outbound-limit" name="outboundLimit" class="form-control"
                                        placeholder="Please Enter" style="border-radius: 8px;"
                                        value="{{ $product->outboundLimit }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="minPurchase">Minimum purchase quantity:</label>
                        <input type="number" id="minPurchase" name="minPurchase" class="form-control"
                            value="{{ $product->minPurchase }}" required style="border-radius: 8px;">
                    </div>

                    <div class="form-group">
                        <label for="shortDescription">Short description:</label>
                        <textarea id="shortDescription" name="shortDescription" class="form-control" placeholder="Please Enter" required
                            style="border-radius: 8px;">{{ $product->shortDescription }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="long-description">Long description:</label>
                        <textarea id="long-description" class="form-control" name='description'
                            placeholder="Type your description here and apply it to your product" maxlength="7000" required
                            style="border-radius: 8px;">{{ $product->description }}</textarea>
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
                                        id="values-hidden-1"
                                        value="{{ str_replace(['[',']','"','\\'], '', $variantTypes[0]['values']) }}">
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
                                    placeholder="e.g., size"
                                    value="{{ $variantTypes[1]['name'] ?? '' }}">
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
                                        id="values-hidden-2"
                                        value="{{ str_replace(['[',']','"','\\'], '', $variantTypes[1]['values']) }}">
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
        
        // Add event listeners for variation changes
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

    // Define existing variations sekali saja di awal
    const existingVariations = {!! ($variations && $variations->count() > 0) ? json_encode($variations->map(function($variation) {
        return [
            'id' => $variation->id,
            'name' => $variation->name,
            'price' => $variation->price,
            'stock' => $variation->stock,
            'msku' => $variation->msku,
            'barcode' => $variation->barcode,
            'variant_image_path' => $variation->variant_image_path
        ];
    })) : '[]' !!};

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
                                    name="variations[${index}][photos]"
                                    accept="image/*"
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

        // Handle new image upload preview
        document.querySelectorAll('.variation-photos').forEach(input => {
            input.addEventListener('change', function(e) {
                const previewId = this.getAttribute('data-preview-id');
                const previewContainer = document.getElementById(previewId);
                const file = this.files[0];

                if (file) {
                    // Hapus preview gambar baru sebelumnya (jika ada)
                    const existingNewPreview = previewContainer.querySelector('.new-image-preview');
                    if (existingNewPreview) {
                        existingNewPreview.remove();
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'new-image-preview position-relative d-inline-block me-2';
                        previewDiv.innerHTML = `
                            <img src="${e.target.result}" 
                                alt="New Variation Image" 
                                style="width: 50px; height: 50px; object-fit: cover;">
                            <span class="remove-new-image position-absolute top-0 end-0" 
                                style="cursor: pointer; background: rgba(255,255,255,0.8); border-radius: 50%; padding: 2px;">
                                <i class="fas fa-times" style="font-size: 12px;"></i>
                            </span>
                        `;
                        previewContainer.appendChild(previewDiv);

                        // Add event listener for removing new image preview
                        previewDiv.querySelector('.remove-new-image').addEventListener('click', function() {
                            previewDiv.remove();
                            input.value = ''; // Clear the file input
                        });
                    };
                    reader.readAsDataURL(file);
                }
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
        <style>
            .chevron {
                width: 16px;
                height: 16px;
                margin-left: auto;
                /* Push to right */
                margin-top: auto;
                /* Center vertically */
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

        <script src="{{ asset('js/category.js') }}"></script>
    @stop

    @section('js')
        <script>
            console.log("Hi, I'm using the Laravel-AdminLTE package!");
        </script>
            <script src="{{ asset('js/category.js') }}"></script>

    @stop
