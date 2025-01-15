<form action="{{ route('products.store') }}" method="POST">
            @csrf
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


                <button type="submit" class="btn btn-primary mt-3" style="border-radius: 8px; width: 100%;">Simpan</button>
            </form>
        </form>