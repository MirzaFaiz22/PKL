<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'length',
        'width',
        'height',
        'weight',
        'length_unit',
        'weight_unit',
        'declare_amount',
        'declare_weight',
        'declare_currency',
        'declare_hs_code',
        'declare_zh_name',
        'declare_en_name',
    ];
}
