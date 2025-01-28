<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use App\Models\VariationType;
use App\Models\VariationTypeValue; 
use App\Models\ProductVariation;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
   public function index()
   {
       $products = Product::paginate(10);
       $services = Service::all(); 
       return view('products.product', compact('products', 'services'));
   }

   public function create()
   {
       return view('products.createDevice');
   }

   public function store(Request $request)
{
    if ($request->input('type') === 'service') {
        return $this->storeService($request);
    }

    DB::beginTransaction();
    
    try {
        $rules = [
            'name' => 'required|max:300',
            'spu' => 'required|max:200|unique:products,spu',
            'fullCategoryId' => 'nullable',
            'brand' => 'nullable|max:20',
            'saleStatus' => 'required|in:FOR_SALE,HOT_SALE,NEW_ARRIVAL,SALE_ENDED',
            'condition' => 'required|in:NEW,USED',
            'hasSelfLife' => 'required|in:true,false',
            'shelfLifeDuration' => 'nullable|required_if:hasSelfLife,true|integer|min:1',
            'inboundLimit' => 'nullable|required_if:hasSelfLife,true|integer|min:0',
            'outboundLimit' => 'nullable|required_if:hasSelfLife,true|integer|min:0',
            'minPurchase' => 'required|integer|min:1',
            'shortDescription' => 'required',
            'description' => 'required|max:7000',
            'hasVariations' => 'boolean',
            'product_images' => 'nullable|array|max:8',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_preorder' => 'boolean',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'customs_chinese_name' => 'nullable|string|max:200',
            'customs_english_name' => 'nullable|string|max:200',
            'hs_code' => 'nullable|string|max:200',
            'invoice_amount' => 'nullable|numeric|min:0',
            'gross_weight' => 'nullable|numeric|min:0',
            'source_url' => 'nullable|string|max:150',
            'purchase_duration' => 'nullable|string',
            'sales_tax_amount' => 'nullable|numeric|min:0',
            'remarks1' => 'nullable|string|max:50',
            'remarks2' => 'nullable|string|max:50',
            'remarks3' => 'nullable|string|max:50',
            
        ];

        if ($request->boolean('hasVariations')) {
            $variantRules = [
                'variantTypes' => 'required|array',
                'variantTypes.*.name' => 'nullable|string|max:255',
                'variantTypes.0.name' => 'required|string|max:255',
                'variantTypes.*.values' => 'nullable',
                'variations' => 'required|array',
                'variations.*.name' => 'required|string',
                'variations.*.price' => 'required|numeric|min:0',
                'variations.*.stock' => 'required|integer|min:0',
                'variations.*.msku' => 'required|string',
                'variations.*.barcode' => 'nullable|string',
                'variations.*.combinations' => 'required',
                'variations.*.photos' => 'nullable|array',
                'variations.*.photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            $rules = array_merge($rules, $variantRules);
        } else {
            $rules = array_merge($rules, [
                'basePrice' => 'required|numeric|min:0',
                'baseStock' => 'required|numeric|min:0|max:999999',
                'baseMsku' => 'required|string|max:255',
                'baseBarcode' => 'nullable|string|max:255',
            ]);
        }

        $validated = $request->validate($rules);

        $validated['hasSelfLife'] = filter_var($validated['hasSelfLife'], FILTER_VALIDATE_BOOLEAN);

        if (is_string($validated['fullCategoryId'])) {
            $validated['fullCategoryId'] = json_decode($validated['fullCategoryId'], true);
        }

        $hasVariations = $validated['hasVariations'] ?? false;
        $variantTypes = $validated['variantTypes'] ?? [];
        $variations = $validated['variations'] ?? [];
        $basePrice = $validated['basePrice'] ?? null;
        $baseStock = $validated['baseStock'] ?? null;
        $baseMsku = $validated['baseMsku'] ?? null;
        $baseBarcode = $validated['baseBarcode'] ?? null;

        unset(
            $validated['hasVariations'],
            $validated['variantTypes'],
            $validated['variations'],
            $validated['basePrice'],
            $validated['baseStock'],
            $validated['baseMsku'],
            $validated['baseBarcode'],
            $validated['product_images']
        );

        $product = Product::create([
            ...$validated,
            'has_variations' => $hasVariations,
            'sold_count' => 0, // Initialize sold count
        ]);

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('product-images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        if ($hasVariations) {
            foreach ($variantTypes as $index => $type) {
                if (!empty($type['name'])) {
                    VariationType::create([
                        'product_id' => $product->id,
                        'name' => $type['name'],
                        'values' => json_encode($type['values'] ?? []),
                        'order' => $index + 1,
                    ]);
                }
            }

            foreach ($variations as $index => $variation) {
                $variantImagePath = null;
                if (isset($variation['photos']) && !empty($variation['photos'])) {
                    $photo = $variation['photos'][0];
                    $variantImagePath = $photo->store('variant-images', 'public');
                }

                ProductVariation::create([
                    'product_id' => $product->id,
                    'name' => $variation['name'],
                    'price' => $variation['price'],
                    'stock' => $variation['stock'],
                    'msku' => $variation['msku'],
                    'barcode' => $variation['barcode'] ?? null,
                    'combinations' => is_string($variation['combinations'])
                        ? $variation['combinations']
                        : json_encode($variation['combinations']),
                    'variant_image_path' => $variantImagePath,
                    'sold_count' => 0, // Initialize sold count
                ]);
            }
        } else {
            ProductVariation::create([
                'product_id' => $product->id,
                'name' => '-',
                'price' => $basePrice,
                'stock' => $baseStock,
                'msku' => $baseMsku,
                'barcode' => $baseBarcode,
                'combinations' => null,
                'sold_count' => 0, // Initialize sold count
            ]);
        }

        DB::commit();

        return $request->expectsJson()
            ? response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load('variationTypes', 'variations', 'images'),
            ])
        : redirect()->route('products.index')->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating product:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function updateSales(Request $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $variationId = $request->input('variation_id');
            $quantity = $request->input('quantity');

            // Update product total sold count
            $product->increment('sold_count', $quantity);

            // Update specific variation sold count
            if ($product->has_variations) {
                $variation = ProductVariation::where('product_id', $product->id)
                    ->where('id', $variationId)
                    ->firstOrFail();
                
                $variation->increment('sold_count', $quantity);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Sales updated successfully'
        ]);
    }

    public function show(Product $product)
    {
        try {
            // Load all necessary relationships with eager loading
            $product->load([
                'variationTypes.values', 
                'variations', 
                'images'
            ]);

            // Retrieve similar products in the same category
            $similarProducts = Product::when($product->fullCategoryId, function ($query) use ($product) {
                    return $query->where('fullCategoryId', $product->fullCategoryId)
                                ->where('id', '!=', $product->id);
                })
                ->limit(4)
                ->get();

            // Prepare additional metadata
            $productMetadata = [
                'totalVariations' => $product->variations->count(),
                'totalImages' => $product->images->count(),
                'totalStock' => $product->variations->sum('stock')
            ];

            // Check inventory status
            $inventoryStatus = $productMetadata['totalStock'] > 0 ? 'Tersedia' : 'Habis';

            // Determine price range for variant products
            $priceRange = $product->has_variations 
                ? [
                    'min' => $product->variations->min('price'),
                    'max' => $product->variations->max('price')
                ]
                : ['min' => $product->variations->first()->price ?? 0];

            // Prepare view data
            $viewData = [
                'product' => $product,
                'similarProducts' => $similarProducts,
                'productMetadata' => $productMetadata,
                'inventoryStatus' => $inventoryStatus,
                'priceRange' => $priceRange
            ];

            // Render the view with prepared data
            return view('products.show', $viewData);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in product show method: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'error' => $e->getTraceAsString()
            ]);

            // Redirect with error message
            return redirect()->route('products.index')
                ->with('error', 'Terjadi kesalahan saat menampilkan detail produk.');
        }
    }

   // Add these methods to your existing ProductController class

   public function edit(Product $product)
   {
        
       // Load necessary relationships
       $product->load('variationTypes', 'variations', 'images');
   
       // Proses variasi tipe
       $variantTypes = $product->variationTypes->map(function ($type) {
           return [
               'name' => $type->name,
               'values' => is_string($type->values) 
                   ? json_decode($type->values, true) 
                   : $type->values ?? []
           ];
       })->toArray();
   
       // Pastikan selalu ada 2 tipe variasi
       while (count($variantTypes) < 2) {
           $variantTypes[] = [
               'name' => '',
               'values' => []
           ];
       }
   
       // Filter variations (exclude default variation with name '-')
       $variations = $product->variations->where('name', '!=', '-')->values();
   
       // Debugging
       \Log::info('Product Variant Types:', $variantTypes);
       \Log::info('Product Variations:', $variations->toArray());
   
       return view('products.productEdit', [
           'product' => $product,
           'variantTypes' => $variantTypes,
           'variations' => $variations
       ]);
   }
    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();
        dd( $request->all());
        try {
            // Define base validation rules
            $rules = [
                'name' => 'required|max:300',
                'spu' => 'required|max:200|unique:products,spu,' . $product->id,
                'fullCategoryId' => 'nullable',
                'brand' => 'nullable|max:20',
                'saleStatus' => 'required|in:FOR_SALE,HOT_SALE,NEW_ARRIVAL,SALE_ENDED',
                'condition' => 'required|in:NEW,USED',
                'hasSelfLife' => 'required|in:true,false',
                'shelfLifeDuration' => 'nullable|required_if:hasSelfLife,true|integer|min:1',
                'inboundLimit' => 'nullable|required_if:hasSelfLife,true|integer|min:0',
                'outboundLimit' => 'nullable|required_if:hasSelfLife,true|integer|min:0',
                'minPurchase' => 'required|integer|min:1',
                'shortDescription' => 'required',
                'description' => 'required|max:7000',
                'hasVariations' => 'boolean',
                'product_images' => 'nullable|array|max:8',
                'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                // Delivery Fields
                'is_preorder' => 'boolean',
                'length' => 'nullable|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'height' => 'nullable|numeric|min:0',
                'weight' => 'nullable|numeric|min:0',
                'customs_chinese_name' => 'nullable|string|max:200',
                'customs_english_name' => 'nullable|string|max:200',
                'hs_code' => 'nullable|string|max:200',
                'invoice_amount' => 'nullable|numeric|min:0',
                'gross_weight' => 'nullable|numeric|min:0',
                'source_url' => 'nullable|string|max:150',
                'purchase_duration' => 'nullable|string',
                'sales_tax_amount' => 'nullable|numeric|min:0',
                'remarks1' => 'nullable|string|max:50',
                'remarks2' => 'nullable|string|max:50',
                'remarks3' => 'nullable|string|max:50'
            ];

            if ($request->boolean('hasVariations')) {
                $variantRules = [
                    'variantTypes' => 'required|array',
                    'variantTypes.*.name' => 'nullable|string|max:255',
                    'variantTypes.0.name' => 'required|string|max:255',
                    'variantTypes.*.values' => 'nullable',
                    'variations' => 'required|array',
                    'variations.*.name' => 'required|string',
                    'variations.*.price' => 'required|numeric|min:0',
                    'variations.*.stock' => 'required|integer|min:0',
                    'variations.*.msku' => 'required|string',
                    'variations.*.barcode' => 'nullable|string',
                    'variations.*.combinations' => 'required',
                    'variations.*.variant_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
                ];
                
                $rules = array_merge($rules, $variantRules);
            } else {
                $rules = array_merge($rules, [
                    'basePrice' => 'required|numeric|min:0',
                    'baseStock' => 'required|numeric|min:0|max:999999',
                    'baseMsku' => 'required|string|max:255',
                    'baseBarcode' => 'nullable|string|max:255'
                ]);
            }

            $validated = $request->validate($rules);
            
            // Convert 'hasSelfLife' to boolean
            $validated['hasSelfLife'] = filter_var($validated['hasSelfLife'], FILTER_VALIDATE_BOOLEAN);

            // Parse 'fullCategoryId' if it's a string
            if (is_string($validated['fullCategoryId'])) {
                $validated['fullCategoryId'] = json_decode($validated['fullCategoryId'], true);
            }

            // Remove variation data from validated array
            $hasVariations = $validated['hasVariations'] ?? false;
            $variantTypes = $validated['variantTypes'] ?? [];
            $variations = $validated['variations'] ?? [];
            $basePrice = $validated['basePrice'] ?? null;
            $baseStock = $validated['baseStock'] ?? null;
            $baseMsku = $validated['baseMsku'] ?? null;
            $baseBarcode = $validated['baseBarcode'] ?? null;

            unset(
                $validated['hasVariations'],
                $validated['variantTypes'],
                $validated['variations'],
                $validated['basePrice'],
                $validated['baseStock'],
                $validated['baseMsku'],
                $validated['baseBarcode'],
                $validated['product_images'],
                $validated['variant_images']
            );

            // Update the product
            $product->update([
                ...$validated,
                'has_variations' => $hasVariations,
            ]);

            // Handle product images
            if ($request->hasFile('product_images')) {
                // Delete old images
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }

                // Upload new images
                foreach ($request->file('product_images') as $image) {
                    $path = $image->store('product-images', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path
                    ]);
                }
            }

            if ($hasVariations) {
                // Delete old variation types and values
                $product->variationTypes()->delete();
                
                // Store new variation types and values
                foreach ($variantTypes as $index => $type) {
                    if (!empty($type['name'])) {
                        VariationType::create([
                            'product_id' => $product->id,
                            'name' => $type['name'],
                            'values' => json_encode($type['values'] ?? []),
                            'order' => $index + 1,
                        ]);
                    }
                }

                // Delete old variations
                $product->variations()->delete();

                // Store new variations
                foreach ($variations as $index => $variation) {
                    // Handle variant image
                    $variantImagePath = null;
                    if ($request->hasFile("variant_images.$index")) {
                        $variantImage = $request->file("variant_images.$index");
                        $variantImagePath = $variantImage->store('variant-images', 'public');
                    }

                    ProductVariation::create([
                        'product_id' => $product->id,
                        'name' => $variation['name'],
                        'price' => $variation['price'],
                        'stock' => $variation['stock'],
                        'msku' => $variation['msku'],
                        'barcode' => $variation['barcode'] ?? null,
                        'combinations' => is_string($variation['combinations']) 
                            ? $variation['combinations'] 
                            : json_encode($variation['combinations']),
                        'variant_image_path' => $variantImagePath
                    ]);
                }
            } else {
                // Update single product variation
                $product->variations()->delete();
                ProductVariation::create([
                    'product_id' => $product->id,
                    'name' => '-',
                    'price' => $basePrice,
                    'stock' => $baseStock,
                    'msku' => $baseMsku,
                    'barcode' => $baseBarcode,
                    'combinations' => null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->load('variationTypes.values', 'variations', 'images')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating product:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            // Delete product images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete variant images from storage
            foreach ($product->variations as $variation) {
                if ($variation->variant_image_path) {
                    Storage::disk('public')->delete($variation->variant_image_path);
                }
            }

            // Delete related records
            $product->images()->delete();
            $product->variationTypes()->delete();
            $product->variations()->delete();
            
            // Delete the product
            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ])->header('Refresh', 'true');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting product:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 422);
        }
    }

   // Methods untuk Service
   public function storeService(Request $request)
   {
       // Validasi input untuk service
       $validated = $request->validate([
           'nama_service' => 'required|string|max:255',
           'harga_beli_service' => 'required|numeric|min:0',
           'kategori' => 'required|string|max:100',
           'satuan' => 'required|string|max:50',
           'harga_jual' => 'required|numeric|min:0'
       ]);

       try {
           Service::create($validated);
           return redirect()->route('products.index')
                          ->with('success', 'Jasa berhasil ditambahkan!');
       } catch (\Exception $e) {
           return redirect()->back()
                          ->with('error', 'Terjadi kesalahan saat menyimpan data!')
                          ->withInput();
       }
   }

   public function editService(Service $service)
   {
       return view('products.servicesEdit', compact('service'));
   }

   public function updateService(Request $request, Service $service)
   {
       $validated = $request->validate([
           'nama_service' => 'required|string|max:255',
           'harga_beli_service' => 'required|numeric|min:0',
           'kategori' => 'required|string|max:100',
           'satuan' => 'required|string|max:50',
           'harga_jual' => 'required|numeric|min:0'
       ]);

       try {
           $service->update($validated);
           return redirect()->route('products.index')
                          ->with('success', 'Data jasa berhasil diperbarui!');
       } catch (\Exception $e) {
           return redirect()->back()
                          ->with('error', 'Terjadi kesalahan saat memperbarui data!')
                          ->withInput();
       }
   }

   public function destroyService(Service $service)
   {
       try {
           $service->delete();
           return redirect()->route('products.index')
                          ->with('success', 'Data jasa berhasil dihapus!');
       } catch (\Exception $e) {
           return redirect()->back()
                          ->with('error', 'Terjadi kesalahan saat menghapus data!');
       }
   }

   public function showService(Service $service)
   {
       return view('services.show', compact('service'));
   }
   
}