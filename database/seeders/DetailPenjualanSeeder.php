<?php

namespace Database\Seeders;

use App\Models\DetailPenjualan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailPenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $detailPenjualans = [
            [
                'penjualan_id' => 1,
                'product_id' => 1,
                'jumlah' => 1,
                'harga_product' => 5000,
                'subtotal' => 5000,
            ],
            [
                'penjualan_id' => 2,
                'product_id' => 2,
                'jumlah' => 1,
                'harga_product' => 5500,
                'subtotal' => 5500,
            ],
        ];

        foreach ($detailPenjualans as $key => $value) {
            DetailPenjualan::create($value);
        }
    }
}
