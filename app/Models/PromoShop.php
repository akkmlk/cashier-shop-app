<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoShop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'buy',
        'get',
        'discount',
        'active',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
