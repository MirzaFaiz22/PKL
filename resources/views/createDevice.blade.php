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
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <h4 style="font-weight: bold;">Basic Information:</h4>

            <div class="form-group">
                <label for="no-product">No Product:</label>
                <input type="text" id="no-product" class="form-control" readonly style="border-radius: 8px;">
            </div>

            <div class="form-group">
            <label for="master-product-name">Master Product Name:</label>
            <input type="text" id="master-product-name" class="form-control" placeholder="Please Enter" maxlength="300" required style="border-radius: 8px;">
            <small>0/300</small>
            </div>

            <div class="form-group">
            <label for="spu">SPU:</label>
            <input type="text" id="spu" class="form-control" placeholder="Please Enter" maxlength="200" required style="border-radius: 8px;">
            <small>0/200</small>
            </div>

            <div class="form-group">
                <label for="master-category">Master Category:</label>
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
                    renderMenu(data); // Render parent categories
                });

                // Tutup dropdown jika klik di luar
                document.addEventListener("click", function () {
                    dropdownMenu.style.display = "none";
                });
            });

            </script>



            <div class="form-group">
            <label for="brand">Brand:</label>
            <input type="text" id="brand" class="form-control" placeholder="1-20 digits of English, Chinese, numbers, spaces and - _ & %" maxlength="20" required style="border-radius: 8px;">
            <small>0/20</small>
            </div>

            <div class="form-group">
            <label for="channel-selling-status">Channel Selling Status:</label>
            <select id="channel-selling-status" class="form-control" style="border-radius: 8px;">
                <option value="selling">Selling</option>
                <option value="hot-sale">Hot Sale</option>
                <option value="new-product">New Product</option>
                <option value="clearance">Clearance</option>
                <option value="discontinued">Discontinued</option>
            </select>
            </div>

            <div class="form-group">
                <label for="condition">Condition:</label>
                <div>
                    <label class="radio-inline">
                        <input type="radio" name="condition" value="new" required> New
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="condition" value="used" required> Used
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="shelf-life">Shelf Life:</label>
                <select id="shelf-life" class="form-control" style="border-radius: 8px;">
                    <option value="no-shelf-life">No Shelf Life</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            <div id="custom-shelf-life" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shelf-life-duration">Shelf Life Duration (days):</label>
                            <input type="number" id="shelf-life-duration" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inbound-limit">Inbound Limit:</label>
                            <input type="number" id="inbound-limit" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="outbound-limit">Outbound Limit:</label>
                            <input type="number" id="outbound-limit" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('shelf-life').addEventListener('change', function () {
                    if (this.value === 'custom') {
                        document.getElementById('custom-shelf-life').style.display = 'block';
                    } else {
                        document.getElementById('custom-shelf-life').style.display = 'none';
                    }
                });
            </script>

            <div class="form-group">
            <label for="min-purchase-quantity">Minimum purchase quantity:</label>
            <input type="number" id="min-purchase-quantity" class="form-control" value="1" required style="border-radius: 8px;">
            </div>

            <div class="form-group">
            <label for="short-description">Short description:</label>
            <textarea id="short-description" class="form-control" placeholder="Please Enter" required style="border-radius: 8px;"></textarea>
            </div>

            <div class="form-group">
            <label for="long-description">Long description:</label>
            <textarea id="long-description" class="form-control" placeholder="Type your description here and apply it to your product" maxlength="7000" required style="border-radius: 8px;"></textarea>
            <small>0/7000</small>
            </div>

            <!-- Product Information-->
            <h4 style="font-weight: bold;">Product Information:</h4>

            <div class="form-group d-flex align-items-center">
                <label for="product-variations" class="mr-2">The product has variations:</label>
                <input type="checkbox" id="product-variations" class="form-control" style="border-radius: 8px; width: auto;">
            </div>

            <div id="variation-fields">
                <div class="row" id="variation-row-1">
                    <!-- Product Fields - These will transform into variation fields when checkbox is checked -->
                    <div class="col-md-6 variation-type variation-dependent" style="display: none;">
                        <div class="form-group">
                            <label for="variation-type-1">First Variation Type:</label>
                            <input type="text" id="variation-type-1" class="form-control" placeholder="e.g., color" required style="border-radius: 8px;">
                        </div>
                    </div>
                    
                    <div class="col-md-6 variation-value variation-dependent" style="display: none;">
                        <div class="form-group">
                            <label for="variation-value-1">Option Value:</label>
                            <div class="d-flex">
                                <input type="text" id="variation-value-1" class="form-control" placeholder="e.g., Red, Blue" style="border-radius: 8px;">
                                <button type="button" id="add-value-1" class="btn btn-primary ml-2">Add</button>
                            </div>
                        </div>
                        <div id="values-list-1" class="mt-2"></div>
                    </div>

                    <div class="col-md-6 variation-type-2 variation-dependent" style="display: none;">
                        <div class="form-group">
                            <label for="variation-type-2">Second Variation Type:</label>
                            <input type="text" id="variation-type-2" class="form-control" placeholder="e.g., size" style="border-radius: 8px;">
                        </div>
                    </div>

                    <div class="col-md-6 variation-value-2 variation-dependent" style="display: none;">
                        <div class="form-group">
                            <label for="variation-value-2">Option Value:</label>
                            <div class="d-flex">
                                <input type="text" id="variation-value-2" class="form-control" placeholder="e.g., S, M, L" style="border-radius: 8px;">
                                <button type="button" id="add-value-2" class="btn btn-primary ml-2">Add</button>
                            </div>
                        </div>
                        <div id="values-list-2" class="mt-2"></div>
                    </div>

                    <!-- Combinations Navigation -->
                    <div id="combinations-nav" class="col-12 mb-3 variation-dependent" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Combinations</h5>
                            </div>
                            <div class="card-body">
                                <div id="combinations-list" class="mb-3"></div>
                                <div id="current-combination" class="alert alert-info"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Base Fields - These will be used for both normal and variation modes -->
                    <div id="base-fields" class="row w-100">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase-price">Purchase Price:</label>
                                <input type="number" id="purchase-price" class="form-control" placeholder="Enter purchase price" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selling-price">Selling Price:</label>
                                <input type="number" id="selling-price" class="form-control" placeholder="Enter selling price" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku">SKU:</label>
                                <input type="text" id="sku" class="form-control" placeholder="Enter SKU" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock">Stock:</label>
                                <input type="number" id="stock" class="form-control" placeholder="Enter stock quantity" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barcode">Barcode:</label>
                                <input type="text" id="barcode" class="form-control" placeholder="Enter barcode" style="border-radius: 8px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mt-3" id="generate-json">Generate JSON</button>

            <pre id="json-output" class="mt-3" style="background: #f5f5f5; padding: 15px;"></pre>

            <script>
            let variationTypes = {
                type1: { name: '', values: [] },
                type2: { name: '', values: [] }
            };

            let variationCombinations = [];
            let currentCombinationIndex = 0;
            let combinationsData = new Map();

            // Toggle variation fields
            document.getElementById('product-variations').addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('.variation-dependent').forEach(el => {
                    el.style.display = isChecked ? 'block' : 'none';
                });
                
                if (!isChecked) {
                    variationTypes = {
                        type1: { name: '', values: [] },
                        type2: { name: '', values: [] }
                    };
                    variationCombinations = [];
                    combinationsData.clear();
                    updateValuesDisplay();
                }
            });

            // Add values for first variation type
            document.getElementById('add-value-1').addEventListener('click', function() {
                const typeName = document.getElementById('variation-type-1').value.trim();
                const value = document.getElementById('variation-value-1').value.trim();
                
                if (typeName && value) {
                    variationTypes.type1.name = typeName;
                    if (!variationTypes.type1.values.includes(value)) {
                        variationTypes.type1.values.push(value);
                        document.getElementById('variation-value-1').value = '';
                        updateValuesDisplay();
                    }
                }
            });

            // Add values for second variation type
            document.getElementById('add-value-2').addEventListener('click', function() {
                const typeName = document.getElementById('variation-type-2').value.trim();
                const value = document.getElementById('variation-value-2').value.trim();
                
                if (typeName && value) {
                    variationTypes.type2.name = typeName;
                    if (!variationTypes.type2.values.includes(value)) {
                        variationTypes.type2.values.push(value);
                        document.getElementById('variation-value-2').value = '';
                        updateValuesDisplay();
                    }
                }
            });

            // Update values display and combinations
            function updateValuesDisplay() {
                // Update first variation type values
                const valuesList1 = document.getElementById('values-list-1');
                valuesList1.innerHTML = variationTypes.type1.values.map(value => `
                    <span class="badge badge-primary mr-2 mb-2" style="padding: 8px;">
                        ${value}
                        <button type="button" class="btn-close" onclick="removeValue('type1', '${value}')">&times;</button>
                    </span>
                `).join('');

                // Update second variation type values
                const valuesList2 = document.getElementById('values-list-2');
                valuesList2.innerHTML = variationTypes.type2.values.map(value => `
                    <span class="badge badge-primary mr-2 mb-2" style="padding: 8px;">
                        ${value}
                        <button type="button" class="btn-close" onclick="removeValue('type2', '${value}')">&times;</button>
                    </span>
                `).join('');

                updateCombinationsDisplay();
            }

            // Update combinations display
            function updateCombinationsDisplay() {
                const combinationsNav = document.getElementById('combinations-nav');
                const combinationsList = document.getElementById('combinations-list');
                const currentComboDisplay = document.getElementById('current-combination');
                
                // Generate combinations
                variationCombinations = [];
                if (variationTypes.type1.values.length > 0) {
                    if (variationTypes.type2.values.length > 0) {
                        // Multiple variants
                        variationTypes.type1.values.forEach(value1 => {
                            variationTypes.type2.values.forEach(value2 => {
                                variationCombinations.push([value1, value2]);
                            });
                        });
                    } else {
                        // Single variant
                        variationTypes.type1.values.forEach(value => {
                            variationCombinations.push([value]);
                        });
                    }
                }

                // Create navigation buttons
                if (variationCombinations.length > 0) {
                    combinationsNav.style.display = 'block';
                    combinationsList.innerHTML = variationCombinations.map((combo, index) => `
                        <button class="btn ${index === currentCombinationIndex ? 'btn-primary' : 'btn-outline-primary'} mr-2 mb-2" 
                                onclick="switchCombination(${index})">
                            ${combo.join(' - ')}
                        </button>
                    `).join('');

                    // Show current combination
                    const currentCombo = variationCombinations[currentCombinationIndex];
                    let comboDisplay = '';
                    if (currentCombo.length === 1) {
                        comboDisplay = `${variationTypes.type1.name}: ${currentCombo[0]}`;
                    } else {
                        comboDisplay = `${variationTypes.type1.name}: ${currentCombo[0]} | ${variationTypes.type2.name}: ${currentCombo[1]}`;
                    }
                    currentComboDisplay.textContent = `Current Combination: ${comboDisplay}`;

                    // Load data for current combination
                    loadCombinationData();
                } else {
                    combinationsNav.style.display = 'none';
                }
            }

            // Switch between combinations
            function switchCombination(index) {
                saveCombinationData();
                currentCombinationIndex = index;
                updateCombinationsDisplay();
            }

            // Save data for current combination
            function saveCombinationData() {
                const currentCombo = variationCombinations[currentCombinationIndex];
                if (currentCombo) {
                    combinationsData.set(currentCombo.join('-'), {
                        purchasePrice: document.getElementById('purchase-price').value,
                        sellingPrice: document.getElementById('selling-price').value,
                        sku: document.getElementById('sku').value,
                        stock: document.getElementById('stock').value,
                        barcode: document.getElementById('barcode').value
                    });
                }
            }

            // Load data for current combination
            function loadCombinationData() {
                const currentCombo = variationCombinations[currentCombinationIndex];
                if (currentCombo) {
                    const data = combinationsData.get(currentCombo.join('-')) || {
                        purchasePrice: '',
                        sellingPrice: '',
                        sku: '',
                        stock: '',
                        barcode: ''
                    };
                    
                    document.getElementById('purchase-price').value = data.purchasePrice;
                    document.getElementById('selling-price').value = data.sellingPrice;
                    document.getElementById('sku').value = data.sku;
                    document.getElementById('stock').value = data.stock;
                    document.getElementById('barcode').value = data.barcode;
                }
            }

            // Remove value
            function removeValue(type, value) {
                variationTypes[type].values = variationTypes[type].values.filter(v => v !== value);
                updateValuesDisplay();
            }

            </script>

            <!-- Media Settings Section -->
            <h4 style="font-weight: bold;" class="mt-4">Media Settings:</h4>

            <div class="form-group">
                <!-- Main Product Images Section -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-circle text-muted me-2" style="font-size: 8px;"></i>
                        <label class="mb-0">Product Image</label>
                        <span class="text-muted ms-2">Max.9</span>
                    </div>
                    <div id="main-images-container">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="image-upload-box main-upload" style="width: 100px; height: 100px; border: 2px dashed #ccc; border-radius: 8px; display: flex; flex-direction: column; justify-content: center; align-items: center; cursor: pointer;">
                                <i class="fas fa-plus text-muted"></i>
                                <input type="file" class="main-product-image" accept="image/*" multiple style="display: none;">
                            </div>
                            <div id="main-image-preview" class="d-flex flex-wrap gap-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Variant Images Section -->
                <div id="variant-images-section" style="display: none;">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-circle text-muted me-2" style="font-size: 8px;"></i>
                            <label class="mb-0">Variant Image</label>
                        </div>
                        <div id="variant-images-container" class="d-flex flex-wrap gap-4">
                            <!-- Variant upload boxes will be added here dynamically -->
                        </div>
                    </div>
                </div>

                <small class="text-muted d-block mt-2">Support format: JPG, JPEG, PNG (Max: 2MB)</small>
            </div>

            <!-- Variant Upload Box Template -->
            <template id="variant-upload-template">
                <div class="variant-upload-group">
                    <div class="image-upload-box variant-upload" style="width: 100px; height: 100px; border: 2px dashed #ccc; border-radius: 8px; display: flex; flex-direction: column; justify-content: center; align-items: center; cursor: pointer;">
                        <i class="fas fa-plus text-muted"></i>
                        <input type="file" class="variant-product-image" accept="image/*" multiple style="display: none;">
                    </div>
                    <div class="variant-preview d-flex flex-wrap gap-2 mt-2"></div>
                    <div class="text-muted small mt-1" style="text-align: center;"></div>
                </div>
            </template>

            <!-- Image Preview Template -->
            <template id="image-preview-template">
                <div class="image-container" style="position: relative; width: 100px;">
                    <img style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                    <div class="image-overlay" style="position: absolute; top: 5px; right: 5px;">
                        <button type="button" class="btn btn-sm btn-light delete-image" title="Delete" style="background: rgba(255,255,255,0.9);">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </template>

            <script>
            class ImageManager {
                constructor() {
                    this.mainImages = [];
                    this.variantImages = new Map();
                    this.MAX_IMAGES = 9;
                    this.MAX_VARIANT_IMAGES = 8;
                    this.setupEventListeners();
                }

                setupEventListeners() {
                    // Main images upload handler
                    document.querySelector('.main-upload').addEventListener('click', () => {
                        document.querySelector('.main-product-image').click();
                    });

                    document.querySelector('.main-product-image').addEventListener('change', (e) => {
                        this.handleFileUpload(e.target.files, 'main');
                    });

                    // Variation checkbox handler
                    const variationCheckbox = document.getElementById('product-variations');
                    if (variationCheckbox) {
                        variationCheckbox.addEventListener('change', (e) => {
                            document.getElementById('variant-images-section').style.display = 
                                e.target.checked ? 'block' : 'none';
                            if (e.target.checked) {
                                this.updateVariantUploadBoxes();
                            }
                        });
                    }

                    // Setup drag and drop for main upload
                    this.setupDragAndDrop(document.querySelector('.main-upload'), 'main');
                }

                setupDragAndDrop(element, type, variantValue = null) {
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        element.addEventListener(eventName, preventDefaults, false);
                    });

                    element.addEventListener('drop', (e) => {
                        const files = e.dataTransfer.files;
                        if (type === 'main') {
                            this.handleFileUpload(files, 'main');
                        } else {
                            this.handleFileUpload(files, 'variant', variantValue);
                        }
                    });

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                }

                handleFileUpload(files, type, variantValue = null) {
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    const maxImages = type === 'main' ? this.MAX_IMAGES : this.MAX_VARIANT_IMAGES;

                    Array.from(files).forEach(file => {
                        if (file.size > maxSize) {
                            alert(`File ${file.name} is too large. Maximum size is 2MB.`);
                            return;
                        }

                        if (!validTypes.includes(file.type)) {
                            alert(`File ${file.name} has invalid format. Please use JPG, JPEG, or PNG.`);
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            if (type === 'main') {
                                if (this.mainImages.length >= maxImages) {
                                    alert(`Maximum ${maxImages} images allowed.`);
                                    return;
                                }
                                this.mainImages.push({
                                    url: e.target.result
                                });
                                this.updateMainDisplay();
                            } else if (variantValue) {
                                const images = this.variantImages.get(variantValue) || [];
                                if (images.length >= maxImages) {
                                    alert(`Maximum ${maxImages} images allowed per variant.`);
                                    return;
                                }
                                images.push({
                                    url: e.target.result
                                });
                                this.variantImages.set(variantValue, images);
                                this.updateVariantDisplay(variantValue);
                            }
                        };
                        reader.readAsDataURL(file);
                    });
                }

                updateMainDisplay() {
                    const previewContainer = document.getElementById('main-image-preview');
                    previewContainer.innerHTML = '';
                    const template = document.getElementById('image-preview-template');

                    this.mainImages.forEach((image, index) => {
                        const clone = template.content.cloneNode(true);
                        const img = clone.querySelector('img');
                        const deleteButton = clone.querySelector('.delete-image');

                        img.src = image.url;

                        deleteButton.addEventListener('click', () => {
                            this.mainImages.splice(index, 1);
                            this.updateMainDisplay();
                        });

                        previewContainer.appendChild(clone);
                    });
                }

                updateVariantDisplay(variantValue) {
                    const variantGroup = document.querySelector(`[data-variant="${variantValue}"]`);
                    if (!variantGroup) return;

                    const previewContainer = variantGroup.querySelector('.variant-preview');
                    const counterElement = variantGroup.querySelector('.text-muted');
                    const images = this.variantImages.get(variantValue) || [];
                    
                    previewContainer.innerHTML = '';
                    const template = document.getElementById('image-preview-template');

                    images.forEach((image, index) => {
                        const clone = template.content.cloneNode(true);
                        const img = clone.querySelector('img');
                        const deleteButton = clone.querySelector('.delete-image');

                        img.src = image.url;

                        deleteButton.addEventListener('click', () => {
                            images.splice(index, 1);
                            this.variantImages.set(variantValue, images);
                            this.updateVariantDisplay(variantValue);
                        });

                        previewContainer.appendChild(clone);
                    });

                    counterElement.textContent = `${variantValue}'s (${images.length}/${this.MAX_VARIANT_IMAGES})`;
                }

                updateVariantUploadBoxes() {
                    const container = document.getElementById('variant-images-container');
                    container.innerHTML = '';
                    const template = document.getElementById('variant-upload-template');

                    // Get all variant values
                    let variantValues = [];
                    if (variationTypes.type1.values.length > 0) {
                        if (variationTypes.type2.values.length > 0) {
                            // Multiple variants
                            variationTypes.type1.values.forEach(value1 => {
                                variationTypes.type2.values.forEach(value2 => {
                                    variantValues.push(`${value1}-${value2}`);
                                });
                            });
                        } else {
                            // Single variant
                            variantValues = variationTypes.type1.values;
                        }
                    }

                    // Create upload box for each variant
                    variantValues.forEach(variant => {
                        const clone = template.content.cloneNode(true);
                        const group = clone.querySelector('.variant-upload-group');
                        const uploadBox = clone.querySelector('.variant-upload');
                        const input = clone.querySelector('.variant-product-image');
                        const counter = clone.querySelector('.text-muted');

                        group.setAttribute('data-variant', variant);
                        counter.textContent = `${variant}'s (0/${this.MAX_VARIANT_IMAGES})`;

                        // Setup click handler
                        uploadBox.addEventListener('click', () => {
                            input.click();
                        });

                        // Setup file change handler
                        input.addEventListener('change', (e) => {
                            this.handleFileUpload(e.target.files, 'variant', variant);
                        });

                        // Setup drag and drop
                        this.setupDragAndDrop(uploadBox, 'variant', variant);

                        container.appendChild(clone);

                        // Update display if images exist
                        this.updateVariantDisplay(variant);
                    });
                }

                getMainImages() {
                    return this.mainImages.length > 0 ? 
                        this.mainImages.map(img => img.url) : 
                        ["https://cdn-oss.ginee.com/erp/prod/default.png"];
                }

                getVariantImages(variant) {
                    const images = this.variantImages.get(variant);
                    return images && images.length > 0 ? 
                        images.map(img => img.url) : 
                        this.getMainImages();
                }
            }

            // Initialize image manager
            const imageManager = new ImageManager();

            // Hook into existing variation type changes
            const originalUpdateValuesDisplay = window.updateValuesDisplay;
            window.updateValuesDisplay = function() {
                if (originalUpdateValuesDisplay) {
                    originalUpdateValuesDisplay();
                }
                if (document.getElementById('product-variations').checked) {
                    imageManager.updateVariantUploadBoxes();
                }
            };

            // Modify generate JSON function
            document.getElementById('generate-json').addEventListener('click', function() {
                saveCombinationData();
                
                const hasVariations = document.getElementById('product-variations').checked;
                let outputData = {};

                if (!hasVariations) {
                    outputData = {
                        variantOptions: [],
                        variations: [{
                            purchasePrice: {},
                            averageCostPrice: {
                                amount: parseInt(document.getElementById('purchase-price').value) || 0,
                                currencyCode: "IDR"
                            },
                            barcode: document.getElementById('barcode').value || '',
                            images: imageManager.getMainImages(),
                            optionValues: ["-"],
                            sellingPrice: {
                                amount: parseInt(document.getElementById('selling-price').value) || 0,
                                currencyCode: "IDR"
                            },
                            sku: document.getElementById('sku').value || '',
                            status: "ACTIVE",
                            stock: {
                                availableStock: parseInt(document.getElementById('stock').value) || 0,
                                safetyAlert: false,
                                safetyStock: 0
                            },
                            type: "NORMAL"
                        }]
                    };
                } else {
                    const variantOptions = [];
                    if (variationTypes.type1.name && variationTypes.type1.values.length) {
                        variantOptions.push({
                            name: variationTypes.type1.name,
                            values: variationTypes.type1.values
                        });
                    }
                    if (variationTypes.type2.name && variationTypes.type2.values.length) {
                        variantOptions.push({
                            name: variationTypes.type2.name,
                            values: variationTypes.type2.values
                        });
                    }

                    const variations = variationCombinations.map(combo => {
                        const comboKey = combo.join('-');
                        const data = combinationsData.get(comboKey) || {};
                        
                        return {
                            purchasePrice: {},
                            barcode: data.barcode || '',
                            images: imageManager.getVariantImages(comboKey),
                            optionValues: combo,
                            sellingPrice: {
                                amount: parseInt(data.sellingPrice) || 0,
                                currencyCode: "IDR"
                            },
                            sku: data.sku || '',
                            stock: {
                                availableStock: parseInt(data.stock) || 0
                            }
                        };
                    });

                    outputData = {
                        variantOptions,
                        variations
                    };
                }

                document.getElementById('json-output').textContent = JSON.stringify(outputData, null, 2);
            });
            </script>

            <h4 style="font-weight: bold;">Delivery:</h4>

            <h5 style="font-weight: bold;">Informasi Produk</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="length">Panjang (cm):</label>
                        <input type="number" id="length" class="form-control" placeholder="Masukkan panjang">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="width">Lebar (cm):</label>
                        <input type="number" id="width" class="form-control" placeholder="Masukkan lebar">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="height">Tinggi (cm):</label>
                        <input type="number" id="height" class="form-control" placeholder="Masukkan tinggi">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="weight">Berat (g):</label>
                        <input type="number" id="weight" class="form-control" placeholder="Masukkan berat">
                    </div>
                </div>
            </div>

            <h4 style="font-weight: bold;">Customs Information:</h4>

            <div id="customs-info">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customs-chinese-name">Customs Chinese Name:</label>
                            <input type="text" id="customs-chinese-name" class="form-control" placeholder="Please Enter" maxlength="200" style="border-radius: 8px;">
                            <small>0/200</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customs-english-name">Customs English Name:</label>
                            <input type="text" id="customs-english-name" class="form-control" placeholder="Please Enter" maxlength="200" style="border-radius: 8px;">
                            <small>0/200</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="hs-code">HS Code:</label>
                            <input type="text" id="hs-code" class="form-control" placeholder="Please Enter" maxlength="200" style="border-radius: 8px;">
                            <small>0/200</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="invoice-amount">Invoice Amount:</label>
                            <input type="text" id="invoice-amount" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gross-weight">Gross Weight (g):</label>
                            <input type="number" id="gross-weight" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>

            <h4 style="font-weight: bold;">Cost Information:</h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="source-url">Source URL:</label>
                        <input type="text" id="source-url" class="form-control" placeholder="Please Enter" maxlength="150" style="border-radius: 8px;">
                        <small>0/150</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="purchase-duration">Purchase Duration:</label>
                        <input type="text" id="purchase-duration" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sales-tax-amount">Sales Tax Amount:</label>
                        <input type="text" id="sales-tax-amount" class="form-control" placeholder="Please Enter" style="border-radius: 8px;">
                    </div>
                </div>
            </div>

            <h4 style="font-weight: bold;">Other Information:</h4>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="remarks1">Remarks1:</label>
                        <input type="text" id="remarks1" class="form-control" placeholder="1-50 digits of English, Chinese, numbers, spaces and - _ & %" maxlength="50" style="border-radius: 8px;">
                        <small>0/50</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="remarks2">Remarks2:</label>
                        <input type="text" id="remarks2" class="form-control" placeholder="1-50 digits of English, Chinese, numbers, spaces and - _ & %" maxlength="50" style="border-radius: 8px;">
                        <small>0/50</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="remarks3">Remarks3:</label>
                        <input type="text" id="remarks3" class="form-control" placeholder="1-50 digits of English, Chinese, numbers, spaces and - _ & %" maxlength="50" style="border-radius: 8px;">
                        <small>0/50</small>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('warehouse').addEventListener('change', function () {
                    if (this.value === 'KONGFU') {
                        document.getElementById('customs-info').style.display = 'block';
                    } else {
                        document.getElementById('customs-info').style.display = 'none';
                    }
                });

                // Set default visibility based on initial selection
                document.getElementById('warehouse').dispatchEvent(new Event('change'));
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
