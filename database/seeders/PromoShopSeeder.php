<?php

namespace Database\Seeders;

use App\Models\PromoShop;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PromoShop::create([
            'name' => 'Promo ulang tahun toko',
            'type' => 'buyget',
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia sunt possimus laborum excepturi doloribus explicabo ipsa vero delectus. Blanditiis, natus.",
            'buy' => 2,
            'get' => 1,
        ]);
        
        PromoShop::create([
            'name' => 'Promo malam takbir',
            'type' => 'discount',
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia sunt possimus laborum excepturi doloribus explicabo ipsa vero delectus. Blanditiis, natus.",
            'discount' => 5.0,
        ]);
    }
}
