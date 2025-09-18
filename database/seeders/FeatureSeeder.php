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
                'name'=> 'penjualan ditempat',
                'mandatory'=>true,
                'is_active'=> true,
                'route'=>'pos',
                'icon'=>'bi bi-shop'
            ],
            [
                'id'=>2,
                'name'=> 'penjualan',
                'mandatory'=>true,
                'is_active'=> true,
                'route'=>'sales',
                'icon'=>'bi bi-cart2'
            ],
            [
                'id'=>3,
                'name'=> 'pembelian',
                'mandatory'=>true,
                'is_active'=>true,
                'route'=>'purchase',
                'icon'=>'bi bi-credit-card'
            ],
            [
                'id'=>4,
                'name'=> 'inventaris',
                'mandatory'=>true,
                'is_active'=>true,
                'route'=>'inventory',
                'icon'=>'bi bi-box-seam'
            ],
            [
                'id'=>5,
                'name'=> 'pelanggan',
                'mandatory'=>false,
                'is_active'=>false,
                'route'=>'customer',
                'icon'=>'bi bi-cash-coin'
            ],
            [
                'id'=>6,
                'name'=> 'supplier',
                'mandatory'=>false,
                'is_active'=>false,
                'route'=>'supplier',
                'icon'=>'bi bi-truck'
            ],
        ]);
        
        DB::table("sub_features")->insert([
            [
                'id'=>1,
                'name'=> 'metode pembayaran',
                'mandatory'=>true,
                'is_active'=> true,
                'features_id'=>1
            ],
            [
                'id'=>2,
                'name'=> 'harga',
                'mandatory'=>true,
                'is_active'=> true,
                'features_id'=>1
            ],
            [
                'id'=>3,
                'name'=> 'diskon',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>1
            ],
            [
                'id'=>5,
                'name'=> 'pengembalian',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>1
            ],
            [
                'id'=>6,
                'name'=> 'metode pembayaran',
                'mandatory'=>true,
                'is_active'=> true,
                'features_id'=>2
            ],
            [
                'id'=>7,
                'name'=> 'harga',
                'mandatory'=>true,
                'is_active'=> true,
                'features_id'=>2
            ],
            [
                'id'=>8,
                'name'=> 'diskon',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>2
            ],
            [
                'id'=>10,
                'name'=> 'pengembalian',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>2
            ],
            [
                'id'=>11,
                'name'=> 'metode',
                'mandatory'=>true,
                'is_active'=>false,
                'features_id'=>4
            ],
            [
                'id'=>12,
                'name'=> 'varian',
                'mandatory'=>false,
                'is_active'=>false,
                'features_id'=>4
            ],
        ]);
    }
}
