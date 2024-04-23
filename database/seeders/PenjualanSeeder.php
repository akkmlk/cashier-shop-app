<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penjualans = [
            [
                'user_id' => 1,
                'pelanggan_id' => 1,
                'nomor_transaksi' => date('Ymd') . '001',
                'tanggal' => date('Y-m-d H:i:s'),
                'subtotal' => 8500,
                'pajak' => 500,
                'total' => 9000,
                'tunai' => 10000,
                'kembalian' => 1000,
            ],
            [
                'user_id' => 2,
                'pelanggan_id' => 2,
                'nomor_transaksi' => date('Ymd') . '002',
                'tanggal' => date('Y-m-d H:i:s'),
                'subtotal' => 8900,
                'pajak' => 500,
                'total' => 9500,
                'tunai' => 10000,
                'kembalian' => 500,
            ],
        ];

        foreach ($penjualans as $value) {
            Penjualan::create($value);
        }
    }
}
