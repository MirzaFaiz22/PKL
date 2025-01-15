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
        'full_category_id',
        'sale_status',
        'condition',
        'short_description',
        'description',
        'variant_options',
        'variations',
        'images',
        'delivery',
        'type',
        'cost_info',
        'status',
        'extra_info',
        'min_purchase',
        'brand',
    ];

    protected $casts = [
        'full_category_id' => 'array',
        'variant_options' => 'array',
        'variations' => 'array',
        'images' => 'array',
        'delivery' => 'array',
        'cost_info' => 'array',
        'extra_info' => 'array',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function cost()
    {
        return $this->hasOne(Cost::class);
    }

    public function extra()
    {
        return $this->hasOne(ProductExtra::class);
    }
}
