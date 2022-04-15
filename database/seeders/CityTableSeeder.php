<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            [
                'name'  => 'RIAU',
            ],
            [
                'name'  => 'CIREBON',
            ],
            [
                'name'  => 'PONOROGO',
            ],
            [
                'name'  => 'PROBOLINGGO',
            ],
            [
                'name'  => 'TRENGGALEK',
            ],
            [
                'name'  => 'SERANG',
            ],
            [
                'name'  => 'JAKARTA',
            ],
            [
                'name'  => 'CIREBON',
            ],
            [
                'name'  => 'SOLO',
            ],
            [
                'name'  => 'BREBES',
            ],
            [
                'name'  => 'TANGERANG',
            ],
            [
                'name'  => 'PEKALONGAN',
            ],
            [
                'name'  => 'SUBANG',
            ],
            [
                'name'  => 'SRAGEN',
            ],
            [
                'name'  => 'MEDAN',
            ],
            [
                'name'  => 'SIDOARJO',
            ],
            [
                'name'  => 'BEKASI',
            ],
            [
                'name'  => 'BOJONEGORO',
            ],
            [
                'name'  => 'SRAGEN',
            ],
            [
                'name'  => 'BANYUMAS',
            ],
            [
                'name'  => 'PACITAN',
            ],
            [
                'name'  => 'LAMPUNG',
            ],
            [
                'name'  => 'BOYOLALI',
            ],
            [
                'name'  => 'MOJOKERTO',
            ],
            [
                'name'  => 'CIANJUR',
            ],
            [
                'name'  => 'TANJUNG PINANG',
            ],
            [
                'name'  => 'KEDIRI',
            ],
            [
                'name'  => 'TASIKMALAYA',
            ],
            [
                'name'  => 'BANGLI',
            ],
        ];

        City::insert($cities);
    }
}
