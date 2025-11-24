<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("features")->insert([
            [
                'id'=>1,
                'name'=> 'PoS - Penjualan Ditempat',
                'mandatory'=>true,
                'is_active'=> true,
                'route'=>'pos',
                'icon'=>'bi bi-shop'
            ],
            [
                'id'=>2,
                'name'=> 'Penjualan',
                'mandatory'=>true,
                'is_active'=> true,
                'route'=>'sales',
                'icon'=>'bi bi-cart2'
            ],
            [
                'id'=>3,
                'name'=> 'Pembelian',
                'mandatory'=>true,
                'is_active'=>true,
                'route'=>'purchase',
                'icon'=>'bi bi-credit-card'
            ],
            [
                'id'=>4,
                'name'=> 'Inventaris',
                'mandatory'=>true,
                'is_active'=>true,
                'route'=>'inventory',
                'icon'=>'bi bi-box-seam'
            ],
            [
                'id'=>5,
                'name'=> 'Pelanggan',
                'mandatory'=>true,
                'is_active'=>true,
                'route'=>'customer',
                'icon'=>'bi bi-cash-coin'
            ],
            [
                'id'=>6,
                'name'=> 'Pemasok',
                'mandatory'=>true,
                'is_active'=>true,
                'route'=>'supplier',
                'icon'=>'bi bi-truck'
            ],
        ]);
        
        DB::table("sub_features")->insert([
            [
                'id'=>1,
                'name'=> 'Metode pembayaran',
                'mandatory'=>true,
                'is_active'=> true,
                'features_id'=>1
            ],
            [
                'id'=>2,
                'name'=> 'Kebijakan harga',
                'mandatory'=>true,
                'is_active'=> true,
                'features_id'=>1
            ],
            [
                'id'=>3,
                'name'=> 'Diskon',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>1
            ],
            [
                'id'=>5,
                'name'=> 'Pengembalian',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>1
            ],
            [
                'id'=>6,
                'name'=> 'Metode pembayaran',
                'mandatory'=>true,
                'is_active'=> true,
                'features_id'=>2
            ],
            [
                'id'=>7,
                'name'=> 'Kebijakan harga',
                'mandatory'=>true,
                'is_active'=> true,
                'features_id'=>2
            ],
            [
                'id'=>8,
                'name'=> 'Diskon',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>2
            ],
            [
                'id'=>10,
                'name'=> 'Pengembalian',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>2
            ],
            [
                'id'=>13,
                'name'=>'Metode pembayaran',
                'mandatory'=>true,
                'is_active'=>true,
                'features_id'=>3
            ],
            [
                'id'=>14,
                'name'=>'Diskon',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>3
            ],
            [
                'id'=>11,
                'name'=> 'Metode',
                'mandatory'=>true,
                'is_active'=>false,
                'features_id'=>4
            ],
            [
                'id'=>12,
                'name'=> 'Varian',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>4
            ],
        ]);
    }
}
