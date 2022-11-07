<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Price;

class PriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cats = [
            [
                'name'  => 'HARGA CASH',
                'category_id' => 27,
                'price' => 2200
            ],
            [
                'name'  => 'HARGA CASH',
                'category_id' => 28,
                'price' => 2950
            ],
            [
                'name'  => 'HARGA CASH',
                'category_id' => 29,
                'price' => 3500
            ],
            [
                'name'  => 'HARGA CASH',
                'category_id' => 30,
                'price' => 4100
            ],
            [
                'name'  => 'HARGA RETUR',
                'category_id' => 27,
                'price' => 2600
            ],
            [
                'name'  => 'HARGA RETUR',
                'category_id' => 28,
                'price' => 3300
            ],
            [
                'name'  => 'HARGA RETUR',
                'category_id' => 29,
                'price' => 4000
            ],
            [
                'name'  => 'HARGA RETUR',
                'category_id' => 30,
                'price' => 4600
            ],
            [
                'name'  => 'HARGA NON RETUR',
                'category_id' => 27,
                'price' => 2400
            ],
            [
                'name'  => 'HARGA NON RETUR',
                'category_id' => 28,
                'price' => 3200
            ],
            [
                'name'  => 'HARGA NON RETUR',
                'category_id' => 29,
                'price' => 3750
            ],
            [
                'name'  => 'HARGA NON RETUR',
                'category_id' => 30,
                'price' => 4400
            ],
        ];
        Price::insert($cats);
    }
}
