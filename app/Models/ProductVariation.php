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
        'combinations'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'combinations' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}