<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Makanan',
            ],
            [
                'nama_kategori' => 'Minuman',
            ],
        ];

        foreach ($kategoris as $key => $value) {
            Kategori::create($value);
        }
    }
}
