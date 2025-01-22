<?php

// app/Models/VariationType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationType extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'values',
        'order'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function values()
    {
        return $this->hasMany(VariationTypeValue::class)->orderBy('order');
    }
}
