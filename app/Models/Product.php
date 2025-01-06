<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'spu',
        'fullCategoryId',
        'saleStatus',
        'condition',
        'shortDescription',
        'description',
        'variantOptions',
        'variations',
        'images',
        'delivery',
        'type',
        'costInfo',
        'status',
        'extraInfo',
        'minPurchase',
        'brand',
    ];

    protected $casts = [
        'fullCategoryId' => 'array',
        'variantOptions' => 'array',
        'variations' => 'array',
        'images' => 'array',
        'delivery' => 'array',
        'costInfo' => 'array',
        'extraInfo' => 'array',
    ];
}
