<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VariantOption;
use App\Models\Variation;

class Product extends Model
{
    protected $fillable = [
        'name',
        'spu',
        'fullCategoryId',
        'brand',
        'saleStatus',
        'condition',
        'hasSelfLife',
        'shelfLifeDuration',
        'inboundLimit',
        'outboundLimit',
        'minPurchase',
        'shortDescription',
        'description',
        'has_variations',
        // Delivery Fields
        'is_preorder',
        'length',
        'width',
        'height',
        'weight',
        'customs_chinese_name',
        'customs_english_name',
        'hs_code',
        'invoice_amount',
        'gross_weight',
        'source_url',
        'purchase_duration',
        'sales_tax_amount',
        'remarks1',
        'remarks2',
        'remarks3'
    ];

    protected $casts = [
        'hasSelfLife' => 'boolean',
        'has_variations' => 'boolean',
        'fullCategoryId' => 'array'
    ];

    public function variationTypes()
    {
        return $this->hasMany(VariationType::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
