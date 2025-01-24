<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'stock',
        'msku',
        'barcode',
        'combinations',
        'variant_image_path',
        'sold_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'combinations' => 'json',
        'sold_count' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}