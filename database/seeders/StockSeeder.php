<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stocks = [
            [
                'product_id' => 1,
                'nama_suplier' => 'Jamaludin',
                'jumlah' => 10,
                'tanggal' => date('Y-m-d', strtotime('-1 week')),
            ],
            [
                'product_id' => 2,
                'nama_suplier' => 'Kamaludin',
                'jumlah' => 15,
                'tanggal' => date('Y-m-d', strtotime('-1 week')),
            ],
        ];

        Product::query()->where('id', 1)->update([
            'stock' => 250,
        ]);
        Product::query()->where('id', 2)->update([
            'stock' => 150,
        ]);

        foreach ($stocks as $key => $value) {
            Stock::create($value);
        }
    }
}
