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
        'sold_count',
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
        'sold_count' => 'integer',
        'has_variations' => 'boolean',
        'fullCategoryId' => 'array'
    ];

// Accessor
    public function getCategoryNameAttribute()
    {
        $categories = [
            '100551' => 'Language Learning & Dictionaries',
            '101560' => 'Science & Maths',
            '100778' => 'E-Books',
            '100779' => 'Others'
        ];

        if (!$this->fullCategoryId) return '-';

        $lastCategoryId = is_array($this->fullCategoryId) 
            ? end($this->fullCategoryId) 
            : json_decode($this->fullCategoryId, true)[0] ?? null;

        return $lastCategoryId ? ($categories[$lastCategoryId] ?? 'Unknown') : '-';
    }

    public function setFullCategoryIdAttribute($value)
    {
        // Ensure it's always stored as a JSON string
        $this->attributes['fullCategoryId'] = is_array($value) 
            ? json_encode($value) 
            : $value;
    }

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

    public function getTotalSalesAttribute()
    {
        return $this->has_variations 
            ? $this->variations()->sum('sold_count') 
            : $this->sold_count;
    }
}
