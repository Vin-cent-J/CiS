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
                'name'=> 'Tunai',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>1
            ],
            [
                'id'=> 2,
                'name'=> 'Transfer',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>1
            ],
            [
                'id'=> 21,
                'name'=> 'Piutang',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>1
            ],
            [
                'id'=> 3,
                'name'=> 'Harga sudah termasuk pajak',
                'mandatory'=>false,
                'is_active'=> true,
                'sub_features_id'=>2
            ],
            [
                'id'=> 4,
                'name'=> 'Jenis diskon',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>3
            ],
            [
                'id'=> 5,
                'name'=> 'Bonus',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>3
            ],
            [
                'id'=> 6,
                'name'=> 'Syarat Diskon',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>3
            ],
            [
                'id'=> 19,
                'name'=> 'Cakupan diskon',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>3
            ],
            [
                'id'=> 7,
                'name'=> 'Saat pembayaran sudah lunas',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>5
            ],
            [
                'id'=> 8,
                'name'=> 'Saat masih ada piutang',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>5
            ],
            [
                'id'=> 9,
                'name'=> 'Tunai',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>6
            ],
            [
                'id'=> 10,
                'name'=> 'Transfer',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>6
            ],
            [
                'id'=> 22,
                'name'=> 'Piutang',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>6
            ],
            [
                'id'=> 11,
                'name'=> 'Harga sudah termasuk pajak',
                'mandatory'=>false,
                'is_active'=> true,
                'sub_features_id'=>7
            ],
            [
                'id'=> 12,
                'name'=> 'Jenis diskon',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>8
            ],
            [
                'id'=> 13,
                'name'=> 'Bonus',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>8
            ],
            [
                'id'=> 20,
                'name'=> 'Cakupan diskon',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>8
            ],
            [
                'id'=> 15,
                'name'=> 'Saat pembayaran sudah lunas',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>10
            ],
            [
                'id'=> 16,
                'name'=> 'Saat masih ada Hutang',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>10
            ],
            [
                'id'=> 17,
                'name'=> 'Perpetual',
                'mandatory'=>false,
                'is_active'=> true,
                'sub_features_id'=>11
            ],
            [
                'id'=> 23,
                'name'=> 'Tunai',
                'mandatory'=>true,
                'is_active'=> true,
                'sub_features_id'=>13
            ],
            [
                'id'=> 24,
                'name'=> 'Transfer',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>13
            ],
            [
                'id'=> 25,
                'name'=> 'Hutang',
                'mandatory'=>false,
                'is_active'=> false,
                'sub_features_id'=>13
            ],
        ]);

        DB::table('detail_configurations')->insert([
            [
                'id'=> 1,
                'name'=> 'Tunai',
                'mandatory'=>true,
                'is_active'=> true,
                'configurations_id'=>4
            ],
            [
                'id'=> 2,
                'name'=> 'Persen',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>4
            ],
            [
                'id'=> 3,
                'name'=> 'Minimal pembelian',
                'mandatory'=>true,
                'is_active'=> true,
                'configurations_id'=>6
            ],
            [
                'id'=> 4,
                'name'=> 'Barang Tertentu',
                'mandatory'=>true,
                'is_active'=> true,
                'configurations_id'=>6
            ],
            [
                'id'=> 5,
                'name'=> 'Kategori tertentu',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>6
            ],
            [
                'id'=> 6,
                'name'=> 'Ganti barang',
                'mandatory'=>true,
                'is_active'=> true,
                'configurations_id'=>7
            ],
            [
                'id'=> 7,
                'name'=> 'Pengembalian uang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>7
            ],
            [
                'id'=> 8,
                'name'=> 'Pengurangan piutang',
                'mandatory'=>true,
                'is_active'=> true,
                'configurations_id'=>8
            ],
            [
                'id'=> 9,
                'name'=> 'Ganti barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>8
            ],
            [
                'id'=> 10,
                'name'=> 'Tunai',
                'mandatory'=>true,
                'is_active'=> true,
                'configurations_id'=>12
            ],
            [
                'id'=> 11,
                'name'=> 'Persen',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>12
            ],
            [
                'id'=> 15,
                'name'=> 'Ganti barang',
                'mandatory'=>true,
                'is_active'=> true,
                'configurations_id'=>15
            ],
            [
                'id'=> 16,
                'name'=> 'Pengembalian uang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>15
            ],
            [
                'id'=> 17,
                'name'=> 'Pengurangan hutang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>16
            ],
            [
                'id'=> 18,
                'name'=> 'Ganti barang',
                'mandatory'=>true,
                'is_active'=> true,
                'configurations_id'=>16
            ],
            [
                'id'=> 19,
                'name'=> 'Per nota',
                'mandatory'=>false,
                'is_active'=> true,
                'configurations_id'=>19
            ],
            [
                'id'=> 20,
                'name'=> 'Per barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>19
            ],
            [
                'id'=> 21,
                'name'=> 'Per nota',
                'mandatory'=>false,
                'is_active'=> true,
                'configurations_id'=>20
            ],
            [
                'id'=> 22,
                'name'=> 'Per barang',
                'mandatory'=>false,
                'is_active'=> false,
                'configurations_id'=>20
            ],
        ]);
    }
}
