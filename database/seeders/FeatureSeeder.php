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
                'status'=> true,
                'route'=>'pos',
                'icon'=>'bi bi-shop'
            ],
            [
                'id'=>2,
                'name'=> 'penjualan',
                'mandatory'=>true,
                'status'=> true,
                'route'=>'sales',
                'icon'=>'bi bi-cart2'
            ],
            [
                'id'=>3,
                'name'=> 'pembelian',
                'mandatory'=>true,
                'status'=>true,
                'route'=>'purchase',
                'icon'=>'bi bi-credit-card'
            ],
            [
                'id'=>4,
                'name'=> 'inventaris',
                'mandatory'=>true,
                'status'=>true,
                'route'=>'inventory',
                'icon'=>'bi bi-box-seam'
            ],
            [
                'id'=>5,
                'name'=> 'laporan',
                'mandatory'=>true,
                'status'=>true,
                'route'=>'report',
                'icon'=>'bi bi-clipboard-data'
            ],
            [
                'id'=>6,
                'name'=> 'pelanggan',
                'mandatory'=>false,
                'status'=>false,
                'route'=>'customer',
                'icon'=>'bi bi-cash-coin'
            ],
            [
                'id'=>7,
                'name'=> 'supplier',
                'mandatory'=>false,
                'status'=>false,
                'route'=>'supplier',
                'icon'=>'bi bi-truck'
            ],
        ]);
        DB::table("sub_features")->insert([
            [
                'id'=>1,
                'name'=> 'metode pembayaran',
                'mandatory'=>true,
                'status'=> true,
                'features_id'=>1
            ],
            [
                'id'=>2,
                'name'=> 'harga',
                'mandatory'=>true,
                'status'=> true,
                'features_id'=>1
            ],
            [
                'id'=>3,
                'name'=> 'diskon',
                'mandatory'=>false,
                'status'=>false,
                'features_id'=>1
            ],
            [
                'id'=>4,
                'name'=> 'pengembalian',
                'mandatory'=>false,
                'status'=>false,
                'features_id'=>1
            ],
            [
                'id'=>5,
                'name'=> 'metode pembayaran',
                'mandatory'=>true,
                'status'=> true,
                'features_id'=>2
            ],
            [
                'id'=>6,
                'name'=> 'harga',
                'mandatory'=>true,
                'status'=> true,
                'features_id'=>2
            ],
            [
                'id'=>7,
                'name'=> 'diskon',
                'mandatory'=>false,
                'status'=>false,
                'features_id'=>2
            ],
            [
                'id'=>8,
                'name'=> 'pengembalian',
                'mandatory'=>false,
                'status'=>false,
                'features_id'=>2
            ],
            [
                'id'=>9,
                'name'=> 'hutang',
                'mandatory'=>false,
                'status'=>false,
                'features_id'=>2
            ],
        ]);
    }
}
