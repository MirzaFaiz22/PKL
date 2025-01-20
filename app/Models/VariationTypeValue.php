<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationTypeValue extends Model
{
    protected $fillable = [
        'variation_type_id',
        'value',
        'order'
    ];

    public function variationType()
    {
        return $this->belongsTo(VariationType::class);
    }
}
