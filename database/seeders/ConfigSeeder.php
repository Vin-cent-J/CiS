<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("configurations")->insert([
            [
                'id'=> 1,
                'name'=> 'tunai',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>1
            ],
            [
                'id'=> 2,
                'name'=> 'transfer',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>1
            ],
            [
                'id'=> 3,
                'name'=> 'pajak',
                'mandatory'=>false,
                'is_active'=> true,
                'sub_features_id'=>2
            ],
            [
                'id'=> 4,
                'name'=> 'jenis diskon',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>3
            ],
            [
                'id'=> 5,
                'name'=> 'bonus',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>3
            ],
            [
                'id'=> 6,
                'name'=> 'syarat diskon',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>3
            ],
            [
                'id'=> 7,
                'name'=> 'lunas',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>5
            ],
            [
                'id'=> 8,
                'name'=> 'hutang',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>5
            ],
            [
                'id'=> 9,
                'name'=> 'tunai',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>6
            ],
            [
                'id'=> 10,
                'name'=> 'transfer',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>6
            ],
            [
                'id'=> 11,
                'name'=> 'pajak',
                'mandatory'=>false,
                'is_active'=> true,
                'sub_features_id'=>7
            ],
            [
                'id'=> 12,
                'name'=> 'jenis diskon',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>8
            ],
            [
                'id'=> 13,
                'name'=> 'bonus',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>8
            ],
            [
                'id'=> 14,
                'name'=> 'syarat diskon',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>8
            ],
            [
                'id'=> 15,
                'name'=> 'lunas',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>10
            ],

            [
                'id'=> 16,
                'name'=> 'hutang',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>10
            ],
            [
                'id'=> 17,
                'name'=> 'perpetual',
                'mandatory'=>false,
                'is_active'=> true,
                'sub_features_id'=>13
            ],
            [
                'id'=> 18,
                'name'=> 'periodik',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>13
            ],
            [
                'id'=> 19,
                'name'=> 'bentuk diskon',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>3
            ],
            [
                'id'=> 20,
                'name'=> 'bentuk diskon',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>8
            ],
        ]);

        DB::table('detail_configurations')->insert([
            [
                'id'=> 1,
                'name'=> 'tunai',
                'mandatory'=>false,
                'is_active'=> true,
                'configurations_id'=>4
            ],
            [
                'id'=> 2,
                'name'=> 'persen',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>4
            ],
            [
                'id'=> 3,
                'name'=> 'minimal',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>6
            ],
            [
                'id'=> 4,
                'name'=> 'jenis',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>6
            ],
            [
                'id'=> 5,
                'name'=> 'barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>6
            ],
            [
                'id'=> 6,
                'name'=> 'ganti barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>7
            ],
            [
                'id'=> 7,
                'name'=> 'pengembalian uang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>7
            ],
            [
                'id'=> 8,
                'name'=> 'pengurangan hutang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>8
            ],
            [
                'id'=> 9,
                'name'=> 'ganti barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>8
            ],
            [
                'id'=> 10,
                'name'=> 'tunai',
                'mandatory'=>false,
                'is_active'=> true,
                'configurations_id'=>12
            ],
            [
                'id'=> 11,
                'name'=> 'persen',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>12
            ],
            [
                'id'=> 12,
                'name'=> 'minimal',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>14
            ],
            [
                'id'=> 13,
                'name'=> 'jenis',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>14
            ],
            [
                'id'=> 14,
                'name'=> 'barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>14
            ],
            [
                'id'=> 15,
                'name'=> 'ganti barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>15
            ],
            [
                'id'=> 16,
                'name'=> 'pengembalian uang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>15
            ],
            [
                'id'=> 17,
                'name'=> 'pengurangan hutang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>16
            ],
            [
                'id'=> 18,
                'name'=> 'ganti barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>16
            ],
            [
                'id'=> 19,
                'name'=> 'per nota',
                'mandatory'=>false,
                'is_active'=> true,
                'configurations_id'=>19
            ],
            [
                'id'=> 20,
                'name'=> 'per barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>19
            ],
            [
                'id'=> 21,
                'name'=> 'per nota',
                'mandatory'=>false,
                'is_active'=> true,
                'configurations_id'=>20
            ],
            [
                'id'=> 22,
                'name'=> 'per barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>20
            ],
        ]);
    }
}
