<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Variation extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'sku';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'spu',
        'sku',
        'optionValues',
        'sellingPrice',
        'averageCostPrice',
        'images',
        'stock',
        'purchasePrice',
        'bundleVariations',
        'barcode',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'optionValues' => 'array',
        'images' => 'array',
        'bundleVariations' => 'array',
        'sellingPrice' => 'json',
        'averageCostPrice' => 'json',
        'purchasePrice' => 'json',
        'stock' => 'json'
    ];

    /**
     * The model's default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE
    ];

    /**
     * Variation status constants
     */
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_DISABLED = 'DISABLED';
    const STATUS_DELETED = 'DELETED';

    /**
     * Get the product that owns the variation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'spu', 'spu');
    }
}