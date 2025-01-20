<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class VariantOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'spu',
        'name',
        'values'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'values' => 'array'
    ];

    /**
     * Get the product that owns the variant option.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'spu', 'spu');
    }
}