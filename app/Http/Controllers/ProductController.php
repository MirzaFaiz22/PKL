<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variation;
use App\Models\VariantOption;
use App\Models\VariationType;
use App\Models\VariationTypeValue;
use App\Models\ProductVariation;
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
        return view('createDevice');
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
                'hasVariations' => 'boolean'
            ];

            // Add conditional validation rules based on hasVariations
            if ($request->hasVariations) {
                $rules = array_merge($rules, [
                    'variantTypes' => 'required|array',
                    'variantTypes.*.name' => 'required|string|max:255',
                    'variantTypes.*.values' => 'nullable|array',
                    'variations' => 'required|array',
                    'variations.*.name' => 'required|string',
                    'variations.*.price' => 'required|numeric|min:0',
                    'variations.*.stock' => 'required|integer|min:0',
                    'variations.*.msku' => 'required|string',
                    'variations.*.barcode' => 'nullable|string',
                    'variations.*.combinations' => 'required'
                ]);
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
                $validated['baseBarcode']
            );

            // Create the product
            $product = Product::create([
                ...$validated,
                'has_variations' => $hasVariations,
            ]);

            if ($hasVariations) {
                // Store variation types and their values
                foreach ($variantTypes as $index => $type) {
                    if (!empty($type['name'])) {
                        // Create variation type
                        $variationType = VariationType::create([
                            'product_id' => $product->id,
                            'name' => $type['name'],
                            'order' => $index + 1,
                        ]);

                        // Create values for this variation type
                        if (!empty($type['values'])) {
                            foreach ($type['values'] as $valueIndex => $value) {
                                VariationTypeValue::create([
                                    'variation_type_id' => $variationType->id,
                                    'value' => $value,
                                    'order' => $valueIndex + 1
                                ]);
                            }
                        }
                    }
                }

                // Store variations
                foreach ($variations as $variation) {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'name' => $variation['name'],
                        'price' => $variation['price'],
                        'stock' => $variation['stock'],
                        'msku' => $variation['msku'],
                        'barcode' => $variation['barcode'] ?? null,
                        'combinations' => is_string($variation['combinations']) 
                            ? $variation['combinations'] 
                            : json_encode($variation['combinations'])
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
                'data' => $product->load('variationTypes.values', 'variations')
            ]);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
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


    public function show($spu)
    {
        $product = Product::with(['variantOptions', 'variations'])->findOrFail($spu);
        return view('products.show', compact('product'));
    }

    public function edit($spu)
    {
        $product = Product::with(['variantOptions', 'variations'])->findOrFail($spu);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $spu)
    {
        // Implementation for update
    }

    public function destroy($spu)
    {
        try {
            DB::beginTransaction();
            
            $product = Product::findOrFail($spu);
            
            // Delete related records
            $product->variantOptions()->delete();
            $product->variations()->delete();
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete product: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to delete product');
        }
    }
}