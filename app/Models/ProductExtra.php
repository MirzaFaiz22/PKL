<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'pre_order',
        'has_shelf_life',
        'shelf_life_period',
        'addition_info',
    ];

    protected $casts = [
        'pre_order' => 'array',
        'addition_info' => 'array',
    ];
}
