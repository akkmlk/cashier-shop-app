<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'promo_shop_id',
        'kode_product',
        'nama_product',
        'harga',
        'stock',
    ];

    public function detail_penjualans()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function promoShop()
    {
        return $this->belongsTo(PromoShop::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
