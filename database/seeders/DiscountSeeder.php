<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('discounts_rules')->insert([
        //     [
        //         'id' => 1,
        //         'name' => 'tunai',
        //         'minimum' => 1,
        //     ],
        //     [
        //         'id' => 2,
        //         'name' => 'persen',
        //         'minimum' => 1,
        //     ],
        // ]);
        DB::table('categories')->insert([
            [
                'name' => 'Lampu',
            ],
            [
                'name' => 'Kabel',
            ],
            [
                'name' => 'Steker',
            ],
            [
                'name' => 'Saklar',
            ],
            [
                'name' => 'Stop Kontak',
            ],
        ]);
        DB::table('customers')->insert([
            [
                'id' => 1,
                'name' => 'pos',
                'phone_number' => 0,
                'address' => '-',
            ]
        ]);
        DB::table('suppliers')->insert([
            [
                'name' => 'umum',
                'phone' => 0,
                'address' => '-',
            ]
        ]);
        DB::table('products')->insert([
            [
                'name' => 'Lampu LED',
                'categories_id' => 1,
                'price' => 15000,
                'stock' => 100,
            ],
            [
                'name' => 'Kabel Listrik',
                'categories_id' => 2,
                'price' => 20000,
                'stock' => 50,
            ],
            [
                'name' => 'Steker Listrik',
                'categories_id' => 3,
                'price' => 5000,
                'stock' => 200,
            ],
            [
                'name' => 'Saklar Tunggal',
                'categories_id' => 4,
                'price' => 8000,
                'stock' => 150,
            ],
            [
                'name' => 'Stop Kontak Ganda',
                'categories_id' => 5,
                'price' => 12000,
                'stock' => 80,
            ],
        ]);

    }
}
