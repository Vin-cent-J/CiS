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
    }
}
