<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            [
                'name'  => 'Eksemplar',
            ],
            [
                'name'  => 'Lembar',
            ],
            [
                'name'  => 'Liter',
            ],
            [
                'name'  => 'KG',
            ],
        ];

        Unit::insert($units);
    }
}
