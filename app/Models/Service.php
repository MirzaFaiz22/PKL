<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_service',
        'nama_service',
        'harga_beli_service',
        'kategori',
        'satuan',
        'harga_jual'
    ];

    // Jika ingin menambahkan formatting untuk harga
    public function getHargaBeliFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_beli_service, 0, ',', '.');
    }

    public function getHargaJualFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }
}