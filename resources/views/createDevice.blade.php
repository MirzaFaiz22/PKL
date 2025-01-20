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
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
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

            <div class="form-group">
                <label for="fullCategoryId">Master Category:</label>
                <div class="category-dropdown">
                    <div class="dropdown-select" id="categorySelect">
                        <span>Select Master Category</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </div>
                    <div class="dropdown-menu" id="categoryMenu"></div>
                </div>
                <input type="hidden" id="fullCategoryId" name="fullCategoryId" />
            </div>

            

            <script>
            document.addEventListener("DOMContentLoaded", function () {
                const data = [
                    {
                        id: "100643",
                        name: "Books & Magazines",
                        parentId: "0",
                        children: [
                            {
                                id: "100777",
                                name: "Books",
                                parentId: "100643",
                                children: [
                                    {
                                        id: "101551",
                                        name: "Language Learning & Dictionaries",
                                        parentId: "100777",
                                        children: [],
                                    },
                                    {
                                        id: "101560",
                                        name: "Science & Maths",
                                        parentId: "100777",
                                        children: [],
                                    },
                                ],
                            },
                            {
                                id: "100778",
                                name: "E-Books",
                                parentId: "100643",
                                children: [],
                            },
                            {
                                id: "100779",
                                name: "Others",
                                parentId: "100643",
                                children: [],
                            },
                        ],
                    },
                ];

                const dropdownSelect = document.getElementById("categorySelect");
                const dropdownMenu = document.getElementById("categoryMenu");
                const fullCategoryId = document.getElementById("fullCategoryId");

                let selectedIds = [];

                function renderMenu(categories) {
                    dropdownMenu.innerHTML = ""; // Hapus isi dropdown sebelumnya
                    categories.forEach((category) => {
                        const li = document.createElement("li");
                        li.textContent = category.name;
                        li.dataset.id = category.id;
                        li.dataset.children = JSON.stringify(category.children);
                        dropdownMenu.appendChild(li);

                        li.addEventListener("click", function (e) {
                            e.stopPropagation();
                            const categoryId = li.dataset.id;
                            const categoryName = li.textContent;
                            const children = JSON.parse(li.dataset.children);

                            selectedIds.push(categoryId);
                            dropdownSelect.querySelector("span").textContent = categoryName;

                            if (children.length > 0) {
                                renderMenu(children); // Tampilkan child category
                            } else {
                                dropdownMenu.style.display = "none"; // Tutup jika tidak ada child
                                fullCategoryId.value = JSON.stringify(selectedIds); // Simpan ID terpilih
                                console.log("Selected Full Category IDs:", selectedIds);
                            }
                        });
                    });

                    dropdownMenu.style.display = "block"; // Tampilkan menu
                }

                // Tampilkan parent categories saat pertama kali klik
                dropdownSelect.addEventListener("click", function (e) {
                    e.stopPropagation();
                    selectedIds = []; // Reset selection
                    renderMenu(data);
                });

                // Tutup dropdown jika klik di luar
                document.addEventListener("click", function () {
                    dropdownMenu.style.display = "none";
                });
            });

            </script>

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

            <!-- In your blade view file, e.g. create-product.blade.php -->
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
                            <!-- Hidden input to store values array -->
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
                                placeholder="e.g., size">
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
                            <!-- Hidden input to store values array -->
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
                            </tr>
                        </thead>
                        <tbody id="variations-body">
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Base Fields (shown when no variations) -->
            <div id="base-fields">
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
                                        <input type="number" name="basePrice" class="form-control" placeholder="Please Enter">
                                    </div>
                                </td>
                                <td>
                                    <input type="number" name="baseStock" class="form-control" placeholder="Should be between 0-999,999">
                                </td>
                                <td>
                                    <input type="text" name="baseMsku" class="form-control" placeholder="Please Enter">
                                </td>
                                <td>
                                    <input type="text" name="baseBarcode" class="form-control" placeholder="Barcode only supports letters, numb...">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Media Settings Section -->
<div class="card mb-4">
    <div class="card-header">
        <h4 class="mb-0">Media Settings</h4>
    </div>
    <div class="card-body">
        <!-- Main Product Images -->
        <div class="mb-4">
            <label class="d-block mb-2">Product Image Max: 9</label>
            <div class="product-images-container d-flex flex-wrap gap-3">
                <div class="image-upload-box" style="width: 120px; height: 120px;">
                    <input type="file" 
                           class="product-image-input" 
                           name="product_images[]" 
                           accept="image/*" 
                           style="display: none;">
                    <div class="upload-placeholder border d-flex align-items-center justify-content-center" 
                         style="width: 100%; height: 100%; cursor: pointer;">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="image-preview" style="display: none; width: 100%; height: 100%;">
                        <img src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger remove-image" 
                                style="position: absolute; top: 5px; right: 5px;">×</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variant Images -->
        <div id="variant-images-container" style="display: none;">
            <label class="d-block mb-2">Variant Images</label>
            <div id="variant-images-grid" class="d-flex flex-wrap gap-4">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

            <script>
                // Store variation values
let variationValues = {
    1: [], // First variation type values
    2: []  // Second variation type values
};

let productImages = []; // Store product images
let variantImages = {}; // Store variant images by combination name

// Handle checkbox change
document.getElementById('product-variations').addEventListener('change', function(e) {
    const variationFields = document.getElementById('variation-fields');
    const baseFields = document.getElementById('base-fields');
    
    if (e.target.checked) {
        variationFields.style.display = 'block';
        baseFields.style.display = 'none';
    } else {
        variationFields.style.display = 'none';
        baseFields.style.display = 'block';
        // Clear all variation values
        variationValues = { 1: [], 2: [] };
        updateVariationLists();
        generateVariationRows();
        updateHiddenInputs();
    }
});

// Add variation value
function addValue(typeNumber) {
    const input = document.getElementById(`variation-value-${typeNumber}`);
    const value = input.value.trim();
    const typeInput = document.getElementById(`variation-type-${typeNumber}`);
    const typeName = typeInput.value.trim();
    
    if (!typeName) {
        alert('Please enter variation type first');
        return;
    }
    
    if (value && !variationValues[typeNumber].includes(value)) {
        variationValues[typeNumber].push(value);
        input.value = ''; // Clear input
        updateVariationLists();
        generateVariationRows();
        updateHiddenInputs();
    }
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

// Update hidden inputs with current values
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

// Generate variation table rows
// Modify the generateVariationRows function to include image upload
function generateVariationRows() {
    const tbody = document.getElementById('variations-body');
    const variantImagesGrid = document.getElementById('variant-images-grid');
    tbody.innerHTML = '';
    variantImagesGrid.innerHTML = '';
    
    // Get variation type names
    const type1Name = document.getElementById('variation-type-1').value || '';
    const type2Name = document.getElementById('variation-type-2').value || '';
    
    // Generate combinations
    let combinations = [];
    if (variationValues[1].length && variationValues[2].length) {
        // Both variation types have values
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
        // Only one variation type has values
        const activeValues = variationValues[1].length ? variationValues[1] : variationValues[2];
        const activeTypeName = variationValues[1].length ? type1Name : type2Name;
        combinations = activeValues.map(val => ({
            name: val,
            combinations: {
                [activeTypeName]: val
            }
        }));
    }
    
    // Create rows and image upload boxes
    combinations.forEach((combination, index) => {
        // Create table row
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
                        placeholder="Please Enter"
                        required>
                </div>
            </td>
            <td>
                <input type="number" 
                    name="variations[${index}][stock]" 
                    class="form-control" 
                    placeholder="Should be between 0-999,999"
                    required>
            </td>
            <td>
                <input type="text" 
                    name="variations[${index}][msku]" 
                    class="form-control" 
                    placeholder="Please Enter"
                    required>
            </td>
            <td>
                <input type="text" 
                    name="variations[${index}][barcode]" 
                    class="form-control" 
                    placeholder="Barcode only supports letters, numb..."
                    required>
            </td>
            <input type="hidden" name="variations[${index}][name]" value="${combination.name}">
            <input type="hidden" name="variations[${index}][combinations]" value='${JSON.stringify(combination.combinations)}'>
        `;
        tbody.appendChild(row);

        // Create variant image upload box
        const imageBox = document.createElement('div');
        imageBox.className = 'variant-image-box position-relative mb-4';
        imageBox.innerHTML = `
            <div class="card" style="width: 200px;">
                <div class="card-header">
                    <h6 class="mb-0">${combination.name}</h6>
                </div>
                <div class="card-body p-2">
                    <div class="image-upload-container position-relative" style="width: 180px; height: 180px;">
                        <input type="file" 
                               class="variant-image-input"
                               name="variant_images[${index}]" 
                               accept="image/*"
                               style="display: none;">
                        <div class="upload-placeholder border d-flex align-items-center justify-content-center position-absolute top-0 start-0 w-100 h-100"
                             style="cursor: pointer; z-index: 1;">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="image-preview position-absolute top-0 start-0 w-100 h-100" style="display: none;">
                            <img src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                            <button type="button" 
                                    class="btn btn-sm btn-danger remove-image position-absolute"
                                    style="top: 5px; right: 5px; z-index: 2;">×</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        variantImagesGrid.appendChild(imageBox);
    });

    // Add CSS to parent container for proper grid layout
    variantImagesGrid.style.cssText = 'display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;';

    // Show variant images container if there are combinations
    const variantImagesContainer = document.getElementById('variant-images-container');
    variantImagesContainer.style.display = combinations.length ? 'block' : 'none';

    // Initialize image upload handlers
    initializeVariantImageHandlers();
}

// Initialize variant image upload handlers
function initializeVariantImageHandlers() {
    // Handle click on upload placeholder
    document.querySelectorAll('.variant-image-box .upload-placeholder').forEach(placeholder => {
        placeholder.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.variant-image-input');
            input.click();
        });
    });

    // Handle file input change
    document.querySelectorAll('.variant-image-box .variant-image-input').forEach(input => {
        input.addEventListener('change', function(e) {
            const container = this.closest('.image-upload-container');
            const preview = container.querySelector('.image-preview');
            const img = preview.querySelector('img');
            const placeholder = container.querySelector('.upload-placeholder');

            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                };

                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Handle remove image button
    document.querySelectorAll('.variant-image-box .remove-image').forEach(button => {
        button.addEventListener('click', function() {
            const container = this.closest('.image-upload-container');
            const input = container.querySelector('.variant-image-input');
            const preview = container.querySelector('.image-preview');
            const placeholder = container.querySelector('.upload-placeholder');

            input.value = ''; // Clear the file input
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
        });
    });
}

// Add styles to document head for proper positioning
const style = document.createElement('style');
style.textContent = `
    .variant-image-box .image-upload-container {
        position: relative;
        overflow: hidden;
    }
    .variant-image-box .image-preview {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #fff;
    }
    .variant-image-box .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .variant-image-box .remove-image {
        z-index: 10;
    }
    #variant-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
`;
document.head.appendChild(style);



// Update the checkbox change handler to reset variant images
document.getElementById('product-variations').addEventListener('change', function(e) {
    const variationFields = document.getElementById('variation-fields');
    const baseFields = document.getElementById('base-fields');
    const variantImagesContainer = document.getElementById('variant-images-container');
    
    if (e.target.checked) {
        variationFields.style.display = 'block';
        baseFields.style.display = 'none';
        variantImagesContainer.style.display = 'block';
    } else {
        variationFields.style.display = 'none';
        baseFields.style.display = 'block';
        variantImagesContainer.style.display = 'none';
        // Clear all variation values
        variationValues = { 1: [], 2: [] };
        updateVariationLists();
        generateVariationRows();
        updateHiddenInputs();
    }
});

// Form submission handler
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();

    const hasVariations = document.getElementById('product-variations').checked;
    const formData = new FormData(this);
    
    if (hasVariations) {
        // Add variation types data
        const type1Name = document.getElementById('variation-type-1').value.trim();
        const type2Name = document.getElementById('variation-type-2').value.trim();
        
        if (type1Name && variationValues[1].length > 0) {
            formData.set('variantTypes[0][name]', type1Name);
            variationValues[1].forEach((value, index) => {
                formData.append(`variantTypes[0][values][${index}]`, value);
            });
        }
        
        if (type2Name && variationValues[2].length > 0) {
            formData.set('variantTypes[1][name]', type2Name);
            variationValues[2].forEach((value, index) => {
                formData.append(`variantTypes[1][values][${index}]`, value);
            });
        }
        
        formData.set('hasVariations', '1');
    } else {
        formData.set('hasVariations', '0');
    }

    // Submit the form
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        if (data.success) {
            alert('Product saved successfully!');
            window.location.href = '/products';
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the product');
    });
});
            </script>

        
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
        let currentServiceNumber = 1;

        function updateNoProduct() {
            document.getElementById('no-product').value = `PRD-${currentProductNumber.toString().padStart(4, '0')}`;
        }

        function updateNoService() {
            document.getElementById('no-service').value = `SVC-${currentServiceNumber.toString().padStart(4, '0')}`;
        }

        // Initialize form

        updateNoService();

        // Handle product form submission

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
