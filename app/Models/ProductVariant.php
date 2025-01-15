<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'option_values',
        'selling_price',
        'average_cost_price',
        'sku',
        'images',
        'stock',
        'purchase_price',
        'bundle_variations',
        'barcode',
        'status',
    ];

    protected $casts = [
        'option_values' => 'array',
        'images' => 'array',
        'stock' => 'array',
        'bundle_variations' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
