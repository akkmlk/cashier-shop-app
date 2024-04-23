<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_id',
        'product_id',
        'jumlah',
        'harga_product',
        'subtotal',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
