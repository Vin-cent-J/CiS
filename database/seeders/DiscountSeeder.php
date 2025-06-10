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
        DB::table('discounts')->insert([
            [
                'id' => 1,
                'name' => 'tunai',
                'discount' => 0,
                'minimum' => 1,
            ],
            [
                'id' => 2,
                'name' => 'persen',
                'discount' => 0,
                'minimum' => 1,
            ],
        ]);
    }
}
