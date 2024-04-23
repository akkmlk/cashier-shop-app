<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'kategori_id' => 1,
                'promo_shop_id' => 2,
                'kode_product' => '0001',
                'nama_product' => 'Chiki Taro',
                'harga' => 5000,
            ],
            [
                'kategori_id' => 2,
                'promo_shop_id' => 1,
                'kode_product' => '0002',
                'nama_product' => 'Le Mineral',
                'harga' => 3500,
            ],
            [
                'kategori_id' => 2,
                'kode_product' => '0003',
                'nama_product' => 'Kopi',
                'harga' => 10000,
            ],
        ];

        foreach ($products as $key => $value) {
            Product::create($value);
        }
    }
}
