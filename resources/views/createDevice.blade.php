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
            <label for="master-category">Select Master Category:</label>
            <select id="master-category" class="form-control" style="border-radius: 8px;">
                <option value="" selected>Select Master Category</option>
                <!-- Add options here -->
            </select>
            </div>

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
                <label for="product-variations" class="mr-2">The product has variations, if your product has different colors and sizes variations, please choose this:</label>
                <input type="checkbox" id="product-variations" class="form-control" style="border-radius: 8px; width: auto;">
            </div>

            <div id="variation-fields">
                <div class="row" id="variation-row-1">
                    <!-- Variation Type -->
                    <div class="col-md-6 variation-dependent" style="display: none;">
                        <div class="form-group">
                            <label for="variation-type-1">Variation Type:</label>
                            <input type="text" id="variation-type-1" class="form-control" placeholder="Enter the name of the variation, e.g., color, etc." required style="border-radius: 8px;">
                        </div>
                    </div>
                    
                    <!-- Option -->
                    <div id="option-input-group" class="form-group variation-dependent" style="display: none;">
                        <label for="option-name">Option Name:</label>
                        <div class="d-flex align-items-center">
                            <input type="text" id="option-name" class="form-control" placeholder="Enter option name, e.g., Red, Blue, etc." style="border-radius: 8px; width: auto; margin-right: 10px;">
                            <button type="button" id="add-option" class="btn btn-primary" style="border-radius: 8px;">Add Option</button>
                        </div>
                    </div>

                    <!-- Options Container -->
                    <div id="options-container">
                        <!-- Input terkait setiap opsi akan muncul di sini -->
                    </div>

                    <!-- Other Fields -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="purchase-price-1">Purchase Price:</label>
                            <input type="number" id="purchase-price-1" class="form-control" placeholder="Enter purchase price" required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selling-price-1">Selling Price:</label>
                            <input type="number" id="selling-price-1" class="form-control" placeholder="Enter selling price" required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku-1">SKU:</label>
                            <input type="text" id="sku-1" class="form-control" placeholder="Enter SKU" required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock-1">Stock:</label>
                            <input type="number" id="stock-1" class="form-control" placeholder="Enter stock quantity" required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="barcode-1">Barcode:</label>
                            <input type="text" id="barcode-1" class="form-control" placeholder="Enter barcode" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-danger mt-3 remove-variation" style="border-radius: 8px;">Remove Variation</button>
            </div>
            <button type="button" class="btn btn-secondary mt-3" style="border-radius: 8px;">+ Add Variation</button>

        <script>
        let variationCount = 1;

            // Add event listener for checkbox
            document.getElementById('product-variations').addEventListener('change', function () {
                const isChecked = this.checked;
                const variationDependentFields = document.querySelectorAll('.variation-dependent');

                variationDependentFields.forEach((field) => {
                    field.style.display = isChecked ? 'block' : 'none';
                });
            });

            // Logic for adding variations dynamically
            document.querySelector('.btn-secondary').addEventListener('click', function () {
                variationCount++;

                const newVariationRow = document.createElement('div');
                newVariationRow.classList.add('row');
                newVariationRow.id = `variation-row-${variationCount}`;

                newVariationRow.innerHTML = `
                    <div class="col-md-6 variation-dependent" style="display: none;">
                        <div class="form-group">
                            <label for="variation-type-${variationCount}">Variation Type:</label>
                            <input type="text" id="variation-type-${variationCount}" class="form-control" placeholder="Enter the name of the variation, e.g., color, etc." required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6 variation-dependent" style="display: none;">
                        <div class="form-group">
                            <label for="option-${variationCount}">Option:</label>
                            <input type="text" id="option-${variationCount}" class="form-control" placeholder="Enter an option, e.g., Red, etc." required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="purchase-price-${variationCount}">Purchase Price:</label>
                            <input type="number" id="purchase-price-${variationCount}" class="form-control" placeholder="Enter purchase price" required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selling-price-${variationCount}">Selling Price:</label>
                            <input type="number" id="selling-price-${variationCount}" class="form-control" placeholder="Enter selling price" required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku-${variationCount}">SKU:</label>
                            <input type="text" id="sku-${variationCount}" class="form-control" placeholder="Enter SKU" required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock-${variationCount}">Stock:</label>
                            <input type="number" id="stock-${variationCount}" class="form-control" placeholder="Enter stock quantity" required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="barcode-${variationCount}">Barcode:</label>
                            <input type="text" id="barcode-${variationCount}" class="form-control" placeholder="Enter barcode" style="border-radius: 8px;">
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger mt-3 remove-variation" style="border-radius: 8px;">Remove Variation</button>
                `;

                const variationDependentFields = newVariationRow.querySelectorAll('.variation-dependent');
                const isChecked = document.getElementById('product-variations').checked;

                variationDependentFields.forEach((field) => {
                    field.style.display = isChecked ? 'block' : 'none';
                });

                newVariationRow.querySelector('.remove-variation').addEventListener('click', function () {
                    newVariationRow.remove();
                });

                document.getElementById('variation-fields').appendChild(newVariationRow);
            });

            // Create Option Fields for each variation dynamically
            function createOptionFields(variationCount, optionName) {
                const optionId = optionName.replace(/\s+/g, '-').toLowerCase(); // Generate a valid ID for the option
                const container = document.createElement('div');
                container.id = `option-${optionId}`;
                container.classList.add('option-group', 'mt-4', 'p-3', 'border', 'rounded');
                container.innerHTML = `
                    <h5 style="font-weight: bold;">Option: ${optionName}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase-price-${optionId}">Purchase Price:</label>
                                <input type="number" id="purchase-price-${optionId}" class="form-control" placeholder="Enter purchase price" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selling-price-${optionId}">Selling Price:</label>
                                <input type="number" id="selling-price-${optionId}" class="form-control" placeholder="Enter selling price" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku-${optionId}">SKU:</label>
                                <input type="text" id="sku-${optionId}" class="form-control" placeholder="Enter SKU" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock-${optionId}">Stock:</label>
                                <input type="number" id="stock-${optionId}" class="form-control" placeholder="Enter stock quantity" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barcode-${optionId}">Barcode:</label>
                                <input type="text" id="barcode-${optionId}" class="form-control" placeholder="Enter barcode" style="border-radius: 8px;">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger remove-option" style="border-radius: 8px;">Remove Option</button>
                `;
                return container;
            }

            // Add Option function for dynamically added variation (this part remains the same)
            function addOption(variationCount) {
                const optionInput = document.getElementById(`option-${variationCount}`);
                const optionName = optionInput.value.trim();

                if (optionName === '') {
                    alert('Option name cannot be empty!');
                    return;
                }

                const optionsContainer = document.getElementById(`variation-row-${variationCount}`).querySelector('.row');
                const existingOption = document.getElementById(`option-${optionName.replace(/\s+/g, '-').toLowerCase()}`);
                if (existingOption) {
                    alert('This option already exists!');
                    return;
                }

                const optionFields = createOptionFields(variationCount, optionName);
                optionsContainer.appendChild(optionFields);

                // Add event listener for remove option button
                optionFields.querySelector('.remove-option').addEventListener('click', function () {
                    optionFields.remove();
                });

                // Reset option input field
                optionInput.value = '';
            }
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
