<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('product.createDevice');
    }

    public function store(Request $request)
{
    DB::beginTransaction();
    dd($request->all());
    try {
        // Define base validation rules
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
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        if ($request->boolean('hasVariations')) {
            $variantRules = [
                'variantTypes' => 'required|array',
                'variantTypes.*.name' => 'nullable|string|max:255', // Tidak wajibkan semua elemen
                'variantTypes.0.name' => 'required|string|max:255', // Hanya wajib untuk elemen pertama
                'variantTypes.*.values' => 'nullable',
                'variations' => 'required|array',
                'variations.*.name' => 'required|string',
                'variations.*.price' => 'required|numeric|min:0',
                'variations.*.stock' => 'required|integer|min:0',
                'variations.*.msku' => 'required|string',
                'variations.*.barcode' => 'nullable|string',
                'variations.*.combinations' => 'required',
                'variations.*.variant_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Menambahkan validasi untuk gambar pada masing-masing variasi
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
        
        // Validate the request
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

        // Create the product
        $product = Product::create([
            ...$validated,
            'has_variations' => $hasVariations,
        ]);

        // Handle product images
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('product-images', 'public'); // Menyimpan gambar ke folder 'product-images'
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }
            

        if ($hasVariations) {
            // Store variation types dan valuenya
            foreach ($variantTypes as $index => $type) {
                if (!empty($type['name'])) {
                    // Create variation type
                    $variationType = VariationType::create([
                        'product_id' => $product->id,
                        'name' => $type['name'],
                        'values' => json_encode($type['values'] ?? []),
                        'order' => $index + 1,
                    ]);
                }
            }
        
            // Store variations and their images
            foreach ($variations as $index => $variation) {
                // Handle variant image path if present
                $variantImagePath = null;
                if ($request->hasFile("variant_images.$index")) {
                    $variantImage = $request->file("variant_images.$index");
                    $variantImagePath = $variantImage->store('variant-images', 'public');
                }
        
                $productVariation = ProductVariation::create([
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
            // Store single product details
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
            'message' => 'Product created successfully',
            'data' => $product->load('variationTypes.values', 'variations', 'images')
        ]);
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollback();
        // Delete any uploaded images if validation fails
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                if ($image->isValid() && Storage::disk('public')->exists($image->hashName())) {
                    Storage::disk('public')->delete($image->hashName());
                }
            }
        }
        Log::error('Validation error:', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Error creating product: ' . $e->getMessage(),
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollback();
        // Delete any uploaded images if there's an error
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                if ($image->isValid() && Storage::disk('public')->exists($image->hashName())) {
                    Storage::disk('public')->delete($image->hashName());
                }
            }
        }
        Log::error('Error creating product:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Error creating product: ' . $e->getMessage()
        ], 422);
    }
}


}