<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggans = [
            [
                'name' => 'Dodo Sidodo',
                'alamat' => 'Bandung',
                'nomor_tlp' => '081736716797',
            ],
            [
                'name' => 'Dodo Sijamal',
                'alamat' => 'Jakarta',
                'nomor_tlp' => '08173671678',
            ],
        ];

        foreach ($pelanggans as $key => $value) {
            Pelanggan::create($value);
        }
    }
}
