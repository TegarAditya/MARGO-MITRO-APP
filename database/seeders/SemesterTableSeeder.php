<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterTableSeeder extends Seeder
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
                'name'  => 'SEMESTER GENAP 2019/2020',
                'start_date'  => "2019-10-01",
                'end_date'  => "2020-01-31",
                'status' => 0
            ],
            [
                'name'  => 'SEMESTER GANJIL 2020/2021',
                'start_date'  => "2020-04-01",
                'end_date'  => "2020-07-01",
                'status' => 0
            ],
            [
                'name'  => 'SEMESTER GENAP 2020/2021',
                'start_date'  => "2020-09-01",
                'end_date'  => "2021-01-31",
                'status' => 0
            ],
            [
                'name'  => 'SEMESTER GANJIL 2021/2022',
                'start_date'  => "2021-07-01",
                'end_date'  => "2021-09-08",
                'status' => 0
            ],
            [
                'name'  => 'SEMESTER GENAP 2021/2022',
                'start_date'  => "2021-10-01",
                'end_date'  => "2022-01-31",
                'status' => 0
            ],
            [
                'name'  => 'SEMESTER GANJIL 2022/2023',
                'start_date'  => "2022-02-01",
                'end_date'  => "2022-08-31",
                'status' => 1
            ],
            [
                'name'  => 'SEMESTER GENAP 2022/2023',
                'start_date'  => "2022-09-08",
                'end_date'  => "2023-01-31",
                'status' => 1
            ],
        ];
        Semester::insert($cats);
    }
}
