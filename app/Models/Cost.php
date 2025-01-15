<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_url',
        'purchasing_time',
        'purchasing_time_unit',
        'sales_tax',
    ];

    protected $casts = [
        'sales_tax' => 'array',
    ];
}
